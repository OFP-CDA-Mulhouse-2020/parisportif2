<?php

namespace App\FormHandler;

use App\Entity\User;
use App\Factory\PaymentFactory;
use App\Repository\TypeOfPaymentRepository;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EditPasswordHandler
{
    private UserRepository $userRepository;
    private UserPasswordEncoderInterface $passwordEncoder;
    private EntityManagerInterface $entityManager;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
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
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } else {
            throw new \LogicException();
        }
    }
}
