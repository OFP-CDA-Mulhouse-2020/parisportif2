<?php

namespace App\FormHandler;

use App\Entity\User;
use App\Factory\PaymentFactory;
use App\Repository\TypeOfPaymentRepository;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class AddMoneyHandler
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

    public function process(FormInterface $addMoneyForm, User $user): void
    {
        $wallet = $user->getWallet();
        $addMoneyData = $addMoneyForm->getData();
        $paymentStatus = $wallet->addMoney($addMoneyData['amount']);

        if ($paymentStatus) {
            $typeOfPayment = $this->typeOfPaymentRepository->findOneBy(
                [
                    'typeOfPayment' => 'External Transfer Add Money To Wallet'
                ]
            );
            $payment = PaymentFactory::makePaymentFromAddMoneyForm($addMoneyData['amount'], $wallet, $typeOfPayment);


            // TODO : Rajouter la validation du type de paiement

            $payment->acceptPayment();

            $this->entityManager->persist($payment);
            $this->entityManager->flush();
        } else {
            throw new \LogicException();
        }
    }
}
