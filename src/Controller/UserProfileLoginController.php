<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditEmailDisabledType;
use App\Form\EditEmailType;
use App\Form\EditPasswordDisabledType;
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
     * @param Request $request
     * @param EditEmailHandler $editEmailHandler
     * @param MailerService $mailerService
     * @param EditPasswordHandler $editedPasswordHandler
     * @return Response
     */
    public function setUserLogin(
        Request $request,
        EditEmailHandler $editEmailHandler,
        MailerService $mailerService,
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

            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                'registration_confirmation_route',
                (string)$user->getId(),
                (string)$user->getEmail()
            );

            $email = $mailerService->generateEmail($user);

            $email->subject('Votre demande de modification d\'email chez Paris Sportifs')
                ->htmlTemplate('email/edit_email_confirmation_email.html.twig');
            $email->context(
                [
                    'user' => $user,
                    'signedUrl' => $signatureComponents->getSignedUrl(),
                    'expiresAt' => $signatureComponents->getExpiresAt()
                ]
            );

            $mailerService->sendEmail($email);

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
