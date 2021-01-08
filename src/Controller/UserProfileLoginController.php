<?php

namespace App\Controller;

use App\Form\EditEmailType;
use App\Form\LoginType;
use App\Form\EditPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/app/user", name="app_user")
 */
class UserProfileLoginController extends AbstractController
{
    /**
     * @Route("/profile/login", name="_profile_login")
     */
    public function userProfileLogin(UserInterface $user): Response
    {
        $formLogin = $this->createForm(LoginType::class, $user);

        return $this->render('user_profile/login.html.twig', [
            'user' => $user,
            'formLogin' => $formLogin->createView(),
            'editMail' => false,
            'editPassword' => false,
        ]);
    }

    /**
     * @Route("/profile/edit/mail", name="_profile_edit_mail")
     */
    public function userProfileEditMail(
        Request $request,
        UserInterface $user
    ): Response {

        $formLogin = $this->createForm(LoginType::class, $user);
        $formMail = $this->createForm(EditEmailType::class);
        $formMail->handleRequest($request);

        if ($formMail->isSubmitted() && $formMail->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('notice', 'Votre email a bien été changé !');

            return $this->redirectToRoute('app_user_profile_login');
        }

        return $this->render('user_profile/login.html.twig', [
            'user' => $user,
            'formLogin' => $formLogin->createView(),
            'formMail' => $formMail->createView(),
            'editMail' => true,
            'editPassword' => false,
        ]);
    }

    /**
     * @Route("/profile/edit/password", name="_profile_edit_password")
     */
    public function userProfileEditPassword(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response {

        $user = $this->getUser();
        $formLogin = $this->createForm(LoginType::class, $user);
        $formPassword = $this->createForm(EditPasswordType::class, $user);
        $formPassword->handleRequest($request);

        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $oldPassword = $request->request->get('edit_password')['oldPassword'];

            // Si l'ancien mot de passe est bon
            if ($passwordEncoder->isPasswordValid($user, $oldPassword)) {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $user->getPlainPassword()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('notice', 'Votre mot de passe a bien été changé !');

                return $this->redirectToRoute('app_user_profile_login');
            } else {
                $formPassword->addError(new FormError('Ancien mot de passe incorrect'));
            }
        }

        return $this->render('user_profile/login.html.twig', [
            'user' => $user,
            'formLogin' => $formLogin->createView(),
            'formPassword' => $formPassword->createView(),
            'editMail' => false,
            'editPassword' => true,
        ]);
    }
}
