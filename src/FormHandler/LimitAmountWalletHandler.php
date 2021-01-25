<?php

namespace App\FormHandler;

use App\Repository\TypeOfPaymentRepository;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class LimitAmountWalletHandler
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

    public function process(FormInterface $walletForm): void
    {
        $walletData = $walletForm->getData();
        $this->entityManager->persist($walletData);
        $this->entityManager->flush();
    }
}
