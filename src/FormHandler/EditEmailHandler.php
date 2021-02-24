<?php

namespace App\FormHandler;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\DatabaseService;
use App\Service\MailerService;
use Symfony\Component\Form\FormInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EditEmailHandler
{
    private UserRepository $userRepository;
    private DatabaseService $databaseService;
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private MailerService $mailerService;

    public function __construct(
        UserRepository $userRepository,
        DatabaseService $databaseService,
        VerifyEmailHelperInterface $verifyEmailHelper,
        MailerService $mailerService
    ) {
        $this->userRepository = $userRepository;
        $this->databaseService = $databaseService;
        $this->verifyEmailHelper = $verifyEmailHelper;
        $this->mailerService = $mailerService;
    }

    public function process(FormInterface $emailForm, User $user): void
    {
        $newEmail = $emailForm->getData()->getEmail();
        $user->setEmail($newEmail);
        $user->setRoles([]);

        $this->databaseService->saveToDatabase($user);

        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'registration_confirmation_route',
            (string)$user->getId(),
            (string)$user->getEmail()
        );

        $email = $this->mailerService->generateEmail($user);

        $email->subject('Votre demande de modification d\'email chez Paris Sportifs')
            ->htmlTemplate('email/edit_email_confirmation_email.html.twig');
        $email->context(
            [
                'user' => $user,
                'signedUrl' => $signatureComponents->getSignedUrl(),
                'expiresAt' => $signatureComponents->getExpiresAt()
            ]
        );

        $this->mailerService->sendEmail($email);
    }
}
