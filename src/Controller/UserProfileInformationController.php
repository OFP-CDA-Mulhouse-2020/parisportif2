<?php

namespace App\Controller;

use App\Entity\CardIdFile;
use App\Form\AddressType;
use App\Form\IdentityType;
use App\FormHandler\EditIdentityHandler;
use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/app/profile", name="app_profile")
 */
class UserProfileInformationController extends AbstractController
{
    /**
     * @Route("/information", name="_information")
     */
    public function getUserInformation(): Response
    {
        $user = $this->getUser();
        $identityForm = $this->createForm(IdentityType::class, $user);

        $address = $user->getAddress();
        $addressForm = $this->createForm(AddressType::class, $address);

        return $this->render('user_profile/information.html.twig', [
            'user' => $user,
            'identityForm' => $identityForm->createView(),
            'addressForm' => $addressForm->createView(),
            'editedIdentity' => false,
            'editedAddress' => false,
        ]);
    }

    /**
     * @Route("/edit/identity", name="_edit_identity")
     */
    public function editUserIdentity(Request $request, EditIdentityHandler $editedIdentityHandler): Response
    {

        $user = $this->getUser();
        $address = $user->getAddress();

        $identityForm = $this->createForm(IdentityType::class, $user);
        $addressForm = $this->createForm(AddressType::class, $address);
        $identityForm->handleRequest($request);

        if ($identityForm->isSubmitted() && $identityForm->isValid()) {
            try {
                $editedIdentityHandler->process($identityForm, $user);
                $this->addFlash('message', 'Votre identité à été modifiée avec succès !');
                return $this->redirectToRoute('app_profile_information');
            } catch (FileException $e) {
                $identityForm->addError(new FormError("Problème dans l\'envoi de la pièce-jointe"));
            } catch (\LogicException $e) {
                $identityForm->addError(new FormError("Vous devez fournir une pièce-jointe !"));
            }
        }

        return $this->render('user_profile/information.html.twig', [
            'user' => $user,
            'identityForm' => $identityForm->createView(),
            'addressForm' => $addressForm->createView(),
            'editedIdentity' => true,
            'editedAddress' => false,
        ]);
    }

    /**
     * @Route("/edit/address", name="_edit_address")
     */
    public function editUserAddress(Request $request): Response
    {
        $user = $this->getUser();
        $address = $user->getAddress();

        $identityForm = $this->createForm(IdentityType::class, $user);
        $addressForm = $this->createForm(AddressType::class, $address);
        $addressForm->handleRequest($request);

        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            $entityManager->flush();

            $this->addFlash('success', 'Votre adresse à été modifiée avec succès !');
            return $this->redirectToRoute('app_profile_information');
        }

        return $this->render('user_profile/information.html.twig', [
            'user' => $user,
            'identityForm' => $identityForm->createView(),
            'addressForm' => $addressForm->createView(),
            'editedIdentity' => false,
            'editedAddress' => true,
        ]);
    }
}
