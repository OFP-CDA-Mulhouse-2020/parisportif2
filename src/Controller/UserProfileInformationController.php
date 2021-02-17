<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use App\Form\AddressDisabledType;
use App\Form\AddressType;
use App\Form\IdentityDisabledType;
use App\Form\IdentityType;
use App\FormHandler\EditIdentityHandler;
use App\Service\DatabaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
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
    public function getUserInformation(
        Request $request,
        EditIdentityHandler $editedIdentityHandler,
        DatabaseService $databaseService
    ): Response {
        $user = $this->getUser();
        assert($user instanceof User);
        $address = $user->getAddress();
        assert($address instanceof Address);

        $identityForm = $this->createForm(IdentityType::class, $user);
        $identityDisabledForm = $this->createForm(IdentityDisabledType::class, $user);

        $addressForm = $this->createForm(AddressType::class, $address);
        $addressDisabledForm = $this->createForm(AddressDisabledType::class, $address);


        $identityForm->handleRequest($request);
        $addressForm->handleRequest($request);

        // Identity Edition :

        if ($identityForm->isSubmitted() && $identityForm->isValid()) {
            try {
                // $uploadDir = $this->getParameter('files_directory');
                $editedIdentityHandler->process($identityForm, $user);
                $this->addFlash('message', 'Votre identité à été modifiée avec succès !');
                return $this->redirectToRoute('app_profile_information');
            } catch (\RuntimeException $e) {
                $identityForm->addError(new FormError("Problème dans l\'envoi de la pièce-jointe"));
            } catch (\LogicException $e) {
                $identityForm->addError(new FormError("Vous devez fournir une pièce-jointe !"));
            }
        }

        // Adresse Edition :
        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            $databaseService->saveToDatabase($address);

            $this->addFlash('success', 'Votre adresse à été modifiée avec succès !');
            return $this->redirectToRoute('app_profile_information');
        }


        return $this->render('user_profile/information.html.twig', [
            'user' => $user,
            'identityForm' => $identityForm->createView(),
            'identityDisabledForm' => $identityDisabledForm->createView(),
            'addressForm' => $addressForm->createView(),
            'addressDisabledForm' => $addressDisabledForm->createView(),


        ]);
    }
}
