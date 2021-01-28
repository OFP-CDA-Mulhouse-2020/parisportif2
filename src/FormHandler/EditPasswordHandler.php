<?php

namespace App\FormHandler;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\DatabaseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EditPasswordHandler
{
    private UserRepository $userRepository;
    private UserPasswordEncoderInterface $passwordEncoder;
    private DatabaseService $databaseService;
    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        DatabaseService $databaseService
    ) {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->databaseService = $databaseService;
    }

    public function process(FormInterface $passwordForm, User $user): void
    {
        $editedPasswordData = $passwordForm->getData();
        $oldPassword = $editedPasswordData->getPlainPassword();

        // Si l'ancien mot de passe est bon
        if ($this->passwordEncoder->isPasswordValid($user, $oldPassword)) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $user->getPlainPassword()
                )
            );
            $this->databaseService->saveToDatabase($user);
        } else {
            throw new \LogicException();
        }
    }
}
