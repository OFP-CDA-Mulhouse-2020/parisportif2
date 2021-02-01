<?php

namespace App\FormHandler;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\DatabaseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EditEmailHandler
{
    private UserRepository $userRepository;
    private DatabaseService $databaseService;
    public function __construct(
        UserRepository $userRepository,
        DatabaseService $databaseService
    ) {
        $this->userRepository = $userRepository;
        $this->databaseService = $databaseService;
    }

    public function process(FormInterface $emailForm, User $user): void
    {
        $newEmail = $emailForm->getData()->getEmail();
        $user->setEmail($newEmail);
        $user->setRoles([]);

        $this->databaseService->saveToDatabase($user);
    }
}
