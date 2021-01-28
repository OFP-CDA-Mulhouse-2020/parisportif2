<?php

namespace App\FormHandler;

use App\Repository\TypeOfPaymentRepository;
use App\Repository\WalletRepository;
use App\Service\DatabaseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class BankAccountHandler
{
    private WalletRepository $walletRepository;
    private TypeOfPaymentRepository $typeOfPaymentRepository;
    private DatabaseService $databaseService;

    public function __construct(
        WalletRepository $walletRepository,
        TypeOfPaymentRepository $typeOfPaymentRepository,
        DatabaseService $databaseService
    ) {
        $this->walletRepository = $walletRepository;
        $this->typeOfPaymentRepository = $typeOfPaymentRepository;
        $this->databaseService = $databaseService;
    }

    public function process(FormInterface $bankAccountForm): void
    {
        $bankAccountData = $bankAccountForm->getData();

        // TODO : Faire l'envoi de la pièce jointe
//        $this->entityManager->persist($bankAccountData);
//        $this->entityManager->flush();
         $this->databaseService->saveToDatabase($bankAccountData);
    }
}
