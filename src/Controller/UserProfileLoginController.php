<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditEmailDisabledType;
use App\Form\EditEmailType;
use App\Form\EditPasswordDisabledType;
use App\Form\EditPasswordType;
use App\FormHandler\EditEmailHandler;
use App\FormHandler\EditPasswordHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/app/profile", name="app_profile")
 */
class UserProfileLoginController extends AbstractController
{

    /**
     * @Route("/login", name="_login")
     */
    public function setUserLogin(
        Request $request,
        EditEmailHandler $editEmailHandler,
        EditPasswordHandler $editedPasswordHandler
    ): Response {
        $user = $this->getUser();
        assert($user instanceof User);
        $passwordForm = $this->createForm(EditPasswordType::class, $user);
        $passwordFormDisabled = $this->createForm(EditPasswordDisabledType::class, $user);
        $emailForm = $this->createForm(EditEmailType::class);
        $emailFormDisabled = $this->createForm(EditEmailDisabledType::class, $user);

        $passwordForm->handleRequest($request);
        $emailForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            try {
                $editedPasswordHandler->process($passwordForm, $user);
                $this->addFlash('success', 'Votre mot de passe a bien été changé !');
                return $this->redirectToRoute('app_profile_login');
            } catch (\LogicException $e) {
                $passwordForm->addError(new FormError('Ancien mot de passe incorrect'));
            }
        }


        if ($emailForm->isSubmitted() && $emailForm->isValid()) {
            $editEmailHandler->process($emailForm, $user);

            $this->addFlash('success', 'Votre email a bien été changé, un email de confirmation vous a été envoyé !');
            return $this->redirectToRoute('app_profile_login');
        }


        return $this->render(
            'user_profile/login.html.twig',
            [
                'user' => $user,
                'emailForm' => $emailForm->createView(),
                'emailFormDisabled' => $emailFormDisabled->createView(),
                'passwordForm' => $passwordForm->createView(),
                'passwordFormDisabled' => $passwordFormDisabled->createView(),
            ]
        );
    }
}
