<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Payment;
use App\Entity\TypeOfPayment;
use App\Entity\User;
use App\Entity\Wallet;
use App\Factory\PaymentFactory;
use App\Repository\PaymentRepository;
use App\Repository\TypeOfPaymentRepository;
use App\Repository\WebsiteWalletRepository;
use Doctrine\ORM\EntityManagerInterface;

class GenerateCartPaymentService
{
    private EntityManagerInterface $entityManager;
    private PaymentRepository $paymentRepository;
    private TypeOfPaymentRepository $typeOfPaymentRepository;
    private WebsiteWalletRepository $websiteWalletRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        PaymentRepository $paymentRepository,
        TypeOfPaymentRepository $typeOfPaymentRepository,
        WebsiteWalletRepository $websiteWalletRepository
    ) {
        $this->entityManager = $entityManager;
        $this->paymentRepository = $paymentRepository;
        $this->typeOfPaymentRepository = $typeOfPaymentRepository;
        $this->websiteWalletRepository = $websiteWalletRepository;
    }

    public function checkUserWallet(User $user): void
    {
        $cart = $user->getCart();
        assert($cart instanceof Cart);
        $wallet = $user->getWallet();
        assert($wallet instanceof Wallet);

        $cart->setSum();
        /** @var float $sum */
        $sum = $cart->getSum();

        $typeOfPayment = $this->typeOfPaymentRepository->findOneBy(
            [
                'typeOfPayment' => 'Internal Transfer Bet Payment'
            ]
        );
        assert($typeOfPayment instanceof TypeOfPayment);
        $websiteWallet = $this->websiteWalletRepository->findAll()[0];

        $payment = PaymentFactory::makePaymentForBetPayment($sum, $wallet, $websiteWallet, $cart, $typeOfPayment);

        /** @var array $sumOfLastWeekPayment */
        $sumOfLastWeekPayment = $this->paymentRepository->findAmountOfLastWeek(
            $wallet->getId(),
            (int) $typeOfPayment->getId()
        );

        $walletStatus = $wallet->betPayment($sum, $sumOfLastWeekPayment['amountOfLastWeek']);
    }


    public function processPayment(User $user): void
    {
        $cart = $user->getCart();
        assert($cart instanceof Cart);
        $wallet = $user->getWallet();
        assert($wallet instanceof Wallet);

        $cart->setSum();
        /** @var float $sum */
        $sum = $cart->getSum();

        $typeOfPayment = $this->typeOfPaymentRepository->findOneBy(
            [
                'typeOfPayment' => 'Internal Transfer Bet Payment'
            ]
        );
        assert($typeOfPayment instanceof TypeOfPayment);
        $websiteWallet = $this->websiteWalletRepository->findAll()[0];

        $payment = PaymentFactory::makePaymentForBetPayment($sum, $wallet, $websiteWallet, $cart, $typeOfPayment);

        $payment->acceptPayment();
        $websiteWallet->addToBalance($sum);

        $items = $payment->getItems();

        foreach ($items as $item) {
            $item->payItem();
            $item->setCart(null);
            $item->setPayment($payment);
            $this->entityManager->persist($item);
        }

        $user->setCart(null);
        $this->entityManager->remove($cart);
        $this->entityManager->persist($user);
        $this->entityManager->persist($payment);
        $this->entityManager->flush();
    }
}
