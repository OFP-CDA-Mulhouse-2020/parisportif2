<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\FormHandler\RegisterHandler;
use App\Security\LoginFormAuthenticator;
use App\Service\DatabaseService;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{
    private VerifyEmailHelperInterface $verifyEmailHelper;

    public function __construct(VerifyEmailHelperInterface $verifyEmailHelper)
    {
        $this->verifyEmailHelper = $verifyEmailHelper;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(
        Request $request,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator,
        RegisterHandler $registerHandler,
        MailerService $mailerService
    ): Response {

        $user = new User();
        $registrationForm = $this->createForm(RegistrationType::class, $user);
        $registrationForm->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $registerHandler->process($registrationForm, $user);

            // do anything else you need here, like send an email

            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                'registration_confirmation_route',
                (string)$user->getId(),
                (string)$user->getEmail()
            );

            $email = $mailerService->generateEmail($user);

            $email->subject('Bienvenue chez Paris Sportifs !')
                ->htmlTemplate('email/register_confirmation_email.html.twig');
            $email->context([
                'user' => $user,
                'signedUrl' => $signatureComponents->getSignedUrl(),
                'expiresAt' => $signatureComponents->getExpiresAt()
            ]);

            $mailerService->sendEmail($email);

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $registrationForm->createView(),
        ]);
    }


    /**
     * @Route("/verify", name="registration_confirmation_route")
     */
    public function verifyUserEmail(Request $request, DatabaseService $databaseService): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        assert($user instanceof User);

        // Do not get the User's Id or Email Address from the Request object
        try {
            $this->verifyEmailHelper->validateEmailConfirmation(
                $request->getUri(),
                (string)$user->getId(),
                (string)$user->getEmail()
            );
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('verify_email_error', $e->getReason());

            return $this->redirectToRoute('app_register');
        }

        $user->setRoles(['ROLE_USER']);
        $databaseService->saveToDatabase($user);


        // Mark your user as verified. e.g. switch a User::verified property to true

        $this->addFlash('success', 'Votre adresse email est validée');

        return $this->redirectToRoute('app');
    }
}
