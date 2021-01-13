<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use App\Form\AddressType;
use App\Form\IdentityType;
use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/app/user", name="app_user")
 */
class UserProfileInformationController extends AbstractController
{
    // /**
    //  * @Route("/profile/information", name="user_profile_information")
    //  */
    // public function index(): Response
    // {
    //     return $this->render('user_profile/information.html.twig', [
    //         'controller_name' => 'UserProfileInformationController',
    //     ]);
    // }

    /**
     * @Route("/profile/information", name="_profile_information")
     */
    public function userProfileInformation(AddressRepository $addressRepository): Response
    {
        $user = $this->getUser();
        $formIdentity = $this->createForm(IdentityType::class, $user);

        $address = $addressRepository->find($user->getAddress());
        $formAddress = $this->createForm(AddressType::class, $address);

        return $this->render('user_profile/information.html.twig', [
            'user' => $user,
            'formIdentity' => $formIdentity->createView(),
            'formAddress' => $formAddress->createView(),
            'editIdentity' => false,
            'editAddress' => false,
        ]);
    }

    /**
     * @Route("/profile/edit/identity", name="_profile_edit_identity")
     */
    public function userProfileEditIdentity(AddressRepository $addressRepository, Request $request): Response
    {
        $user = $this->getUser();
        $formIdentity = $this->createForm(IdentityType::class, $user);

        $address = $addressRepository->find($user->getAddress());
        $formAddress = $this->createForm(AddressType::class, $address);
        $formIdentity->handleRequest($request);

        if ($formIdentity->isSubmitted() && $formIdentity->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('message', 'Votre identité à été modifiée avec succès !');
            return $this->redirectToRoute('app_user_profile_information');
        }

        return $this->render('user_profile/information.html.twig', [
            'user' => $user,
            'formIdentity' => $formIdentity->createView(),
            'formAddress' => $formAddress->createView(),
            'editIdentity' => true,
            'editAddress' => false,
        ]);
    }

    /**
     * @Route("/profile/edit/address", name="_profile_edit_address")
     */
    public function userProfileEditAddress(AddressRepository $addressRepository, Request $request): Response
    {
        $user = $this->getUser();
        $formIdentity = $this->createForm(IdentityType::class, $user);

        $address = $addressRepository->find($user->getAddress());
        $formAddress = $this->createForm(AddressType::class, $address);
        $formAddress->handleRequest($request);

        if ($formAddress->isSubmitted() && $formAddress->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            $entityManager->flush();

            $this->addFlash('message', 'Votre adresse à été modifiée avec succès !');
            return $this->redirectToRoute('app_user_profile_information');
        }

        return $this->render('user_profile/information.html.twig', [
            'user' => $user,
            'formIdentity' => $formIdentity->createView(),
            'formAddress' => $formAddress->createView(),
            'editIdentity' => false,
            'editAddress' => true,
        ]);
    }

    // /**
    //  * @Route("profile/edit/address/post", name="_edit_address")
    //  */
    // public function editAddress(Address $address, Request $request)
    // {
    //     $formAddress = $this->createForm(AddressType::class, $address);
    //     $formAddress->handleRequest($request);

    //     if ($formAddress->isSubmitted() && $formAddress->isValid()) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($address);
    //         $entityManager->flush();

    //         $this->addFlash('message', 'Votre adresse à été modifiée avec succès !');
    //         return $this->redirectToRoute('_profile_edit_address');
    //     } else {
    //         dd('invalid');
    //     }

    //     $formAddress->addError(new FormError('Votre adresse est incorrecte'));
    // }
}
