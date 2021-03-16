<?php

namespace App\Factory;

use App\Entity\Cart;
use App\Entity\Payment;
use App\Entity\TypeOfPayment;
use App\Entity\Wallet;
use App\Entity\WebsiteWallet;

class PaymentFactory
{
    public static function makePaymentFromAddMoneyForm(
        float $sum,
        Wallet $wallet,
        TypeOfPayment $typeOfPayment
    ): Payment {
        $payment = new Payment($sum);
        $payment->setPaymentName('Ajout de fonds');
        $payment->setWallet($wallet);
        $payment->setTypeOfPayment($typeOfPayment);

        return $payment;
    }

    public static function makePaymentFromWithdrawMoneyForm(
        float $sum,
        Wallet $wallet,
        TypeOfPayment $typeOfPayment
    ): Payment {
        $payment = new Payment($sum);
        $payment->setPaymentName('Retrait de fonds');
        $payment->setWallet($wallet);
        $payment->setTypeOfPayment($typeOfPayment);

        return $payment;
    }

    public static function makePaymentForBetPayment(
        float $sum,
        Wallet $wallet,
        WebsiteWallet $websiteWallet,
        Cart $cart,
        TypeOfPayment $typeOfPayment
    ): Payment {


        $payment = new Payment($sum);
        $payment->setWallet($wallet);
        $payment->setWebsiteWallet($websiteWallet);
        $payment->setItems($cart->getItems());
        assert($cart instanceof Cart);
        $payment->setPaymentName('Ticket de test N°');
        $payment->setTypeOfPayment($typeOfPayment);

        return $payment;
    }

    public static function makePaymentForBetEarning(
        float $sum,
        Wallet $wallet,
        WebsiteWallet $websiteWallet,
        TypeOfPayment $typeOfPayment
    ): Payment {

        $payment = new Payment($sum);
        $payment->setWallet($wallet);
        $payment->setWebsiteWallet($websiteWallet);
        $payment->setPaymentName('Gain sur ticket de pari n°');
        $payment->setTypeOfPayment($typeOfPayment);

        return $payment;
    }
}
