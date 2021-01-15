<?php

namespace App\FormHandler;

use App\Repository\TypeOfPaymentRepository;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class BankAccountHandler
{
    private WalletRepository $walletRepository;
    private TypeOfPaymentRepository $typeOfPaymentRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        WalletRepository $walletRepository,
        TypeOfPaymentRepository $typeOfPaymentRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->walletRepository = $walletRepository;
        $this->typeOfPaymentRepository = $typeOfPaymentRepository;
        $this->entityManager = $entityManager;
    }

    public function process(FormInterface $bankAccountForm): void
    {
        $bankAccountData = $bankAccountForm->getData();

        // TODO : Faire l'envoi de la pièce jointe
        $this->entityManager->persist($bankAccountData);
        $this->entityManager->flush();
    }
}
