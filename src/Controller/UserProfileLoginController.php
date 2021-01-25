<?php

namespace App\Controller;

use App\Form\EditEmailType;
use App\Form\LoginType;
use App\Form\EditPasswordType;
use App\FormHandler\EditPasswordHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/app/profile", name="app_profile")
 */
class UserProfileLoginController extends AbstractController
{
    /**
     * @Route("/login", name="_login")
     */
    public function getUserLogin(UserInterface $user): Response
    {
        $loginForm = $this->createForm(LoginType::class, $user);

        return $this->render('user_profile/login.html.twig', [
            'user' => $user,
            'loginForm' => $loginForm->createView(),
            'editedEmail' => false,
            'editedPassword' => false,
        ]);
    }

    /**
     * @Route("/edit/email", name="_edit_email")
     */
    public function editUserMail(
        Request $request,
        UserInterface $user
    ): Response {

        $loginForm = $this->createForm(LoginType::class, $user);
        $emailForm = $this->createForm(EditEmailType::class);
        $emailForm->handleRequest($request);

        if ($emailForm->isSubmitted() && $emailForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('notice', 'Votre email a bien été changé !');
            return $this->redirectToRoute('app_profile_login');
        }

        return $this->render('user_profile/login.html.twig', [
            'user' => $user,
            'loginForm' => $loginForm->createView(),
            'emailForm' => $emailForm->createView(),
            'editedEmail' => true,
            'editedPassword' => false,
        ]);
    }

    /**
     * @Route("/edit/password", name="_edit_password")
     */
    public function editUserPassword(
        Request $request,
        EditPasswordHandler $editedPasswordHandler
    ): Response {

        $user = $this->getUser();
        $loginForm = $this->createForm(LoginType::class, $user);
        $passwordForm = $this->createForm(EditPasswordType::class, $user);
        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            try {
                $editedPasswordHandler->process($passwordForm, $user);
                $this->addFlash('success', 'Votre mot de passe a bien été changé !');
                return $this->redirectToRoute('app_profile_login');
            } catch (\LogicException $e) {
                $passwordForm->addError(new FormError('Ancien mot de passe incorrect'));
            }
        }
        return $this->render('user_profile/login.html.twig', [
            'user' => $user,
            'loginForm' => $loginForm->createView(),
            'passwordForm' => $passwordForm->createView(),
            'editedEmail' => false,
            'editedPassword' => true,
        ]);
    }
}
