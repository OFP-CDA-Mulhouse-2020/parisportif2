<?php

namespace App\Controller;

use App\Form\AddressType;
use App\Form\IdentityType;
use App\Form\LoginType;
use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/app/user", name="app_user")
 */
class UserProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="_profile")
     */
    public function userProfile(): Response
    {
        return $this->render('user_profile/user_profile.html.twig', [
            'controller_name' => 'UserProfileController',
        ]);
    }

    /**
     * @Route("/profile/connexion", name="_profile_connexion")
     */
    public function userProfileConnexion(UserInterface $user): Response
    {
        $formIdentity = $this->createForm(LoginType::class, $user);

        return $this->render('user_profile/connexion.html.twig', [
            'user' => $user,
            'formConnexion' => $formIdentity->createView(),
            'editMail' => false,
            'editPassword' => false,
        ]);
    }

    /**
     * @Route("/profile/edit/mail", name="_profile_edit_mail")
     */
    public function userProfileEditMail(UserInterface $user): Response
    {
        $formIdentity = $this->createForm(LoginType::class, $user);

        return $this->render('user_profile/connexion.html.twig', [
            'user' => $user,
            'formConnexion' => $formIdentity->createView(),
            'editMail' => true,
            'editPassword' => false,
        ]);
    }

    /**
     * @Route("/profile/edit/password", name="_profile_edit_password")
     */
    public function userProfileEditPassword(UserInterface $user): Response
    {
        $formIdentity = $this->createForm(LoginType::class, $user);

        return $this->render('user_profile/connexion.html.twig', [
            'user' => $user,
            'formConnexion' => $formIdentity->createView(),
            'editMail' => false,
            'editPassword' => true,
        ]);
    }

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
    public function userProfileEditIdentity(AddressRepository $addressRepository): Response
    {
        $user = $this->getUser();
        $formIdentity = $this->createForm(IdentityType::class, $user);

        $address = $addressRepository->find($user->getAddress());
        $formAddress = $this->createForm(AddressType::class, $address);

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
    public function userProfileEditAddress(AddressRepository $addressRepository): Response
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
            'editAddress' => true,
        ]);
    }

    /**
     * @Route("/profile/activation", name="_profile_activation")
     */
    public function userProfileActivation(UserInterface $user): Response
    {

        return $this->render('user_profile/activation.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/profile/suspend", name="_profile_suspend")
     */
    public function userProfileSuspend(UserInterface $user): Response
    {

        return $this->render('user_profile/suspend.html.twig', [
            'user' => $user,
        ]);
    }
}
