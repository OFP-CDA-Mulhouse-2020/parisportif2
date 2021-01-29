<?php

namespace App\FormHandler;

use App\Entity\User;
use App\Repository\TypeOfPaymentRepository;
use App\Repository\WalletRepository;
use App\Service\DatabaseService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterHandler
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private DatabaseService $databaseService;

    public function __construct(
        DatabaseService $databaseService,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->databaseService = $databaseService;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function process(FormInterface $registrationForm, User $user): void
    {
        // encode the plain password
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $registrationForm->get('plainPassword')->getData()
            )
        );
        $user->setCreateAt(new DateTime());
            //->setRoles(['ROLE_USER']);

        $this->databaseService->saveToDatabase($user);
    }
}
