<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditEmailType;
use App\Form\LoginType;
use App\Form\EditPasswordType;
use App\FormHandler\EditEmailHandler;
use App\FormHandler\EditPasswordHandler;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

/**
 * @Route("/app/profile", name="app_profile")
 */
class UserProfileLoginController extends AbstractController
{
    private VerifyEmailHelperInterface $verifyEmailHelper;

    public function __construct(VerifyEmailHelperInterface $verifyEmailHelper)
    {
        $this->verifyEmailHelper = $verifyEmailHelper;
    }

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
        EditEmailHandler $editEmailHandler,
        MailerService $mailerService
    ): Response {
        $user = $this->getUser();
        assert($user instanceof User);
        $loginForm = $this->createForm(LoginType::class, $user);
        $emailForm = $this->createForm(EditEmailType::class);
        $emailForm->handleRequest($request);

        if ($emailForm->isSubmitted() && $emailForm->isValid()) {
            $editEmailHandler->process($emailForm, $user);


            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                'registration_confirmation_route',
                (string)$user->getId(),
                (string)$user->getEmail()
            );

            $email = $mailerService->generateEmail($user);

            $email->subject('Votre demande de modification d\'email chez Paris Sportifs')
                ->htmlTemplate('email/edit_email_confirmation_email.html.twig');
            $email->context([
                'user' => $user,
                'signedUrl' => $signatureComponents->getSignedUrl(),
                'expiresAt' => $signatureComponents->getExpiresAt()
            ]);

            $mailerService->sendEmail($email);

            $this->addFlash('notice', 'Votre email a bien été changé, un email de confirmation vous a été envoyé !');
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
        assert($user instanceof User);
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
