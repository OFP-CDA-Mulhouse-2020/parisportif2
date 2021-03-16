<?php

namespace App\FormHandler;

use App\Repository\TypeOfPaymentRepository;
use App\Repository\WalletRepository;
use App\Service\DatabaseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class LimitAmountWalletHandler
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

    public function process(FormInterface $walletForm): void
    {
        $walletData = $walletForm->getData();

        $this->databaseService->saveToDatabase($walletData);
    }
}
