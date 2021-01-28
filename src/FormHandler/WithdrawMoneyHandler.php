<?php

namespace App\FormHandler;

use App\Entity\User;
use App\Factory\PaymentFactory;
use App\Repository\TypeOfPaymentRepository;
use App\Repository\WalletRepository;
use App\Service\DatabaseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class WithdrawMoneyHandler
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

    public function process(FormInterface $withdrawMoneyForm, User $user): void
    {
        $wallet = $user->getWallet();
        $withdrawMoneyData = $withdrawMoneyForm->getData();
        $paymentStatus = $wallet->withdrawMoney($withdrawMoneyData['amount']);

        if ($paymentStatus) {
            $typeOfPayment = $this->typeOfPaymentRepository->findOneBy(
                [
                    'typeOfPayment' => 'External Transfer Withdraw Money From Wallet'
                ]
            );
            $payment = PaymentFactory::makePaymentFromWithdrawMoneyForm(
                $withdrawMoneyData['amount'],
                $wallet,
                $typeOfPayment
            );

            // TODO : Rajouter la validation du type de paiement

            $payment->acceptPayment();

            $this->databaseService->saveToDatabase($payment);
        } else {
            throw new \LogicException();
        }
    }
}
