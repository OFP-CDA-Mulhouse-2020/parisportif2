<?php

namespace App\Factory;

use App\Entity\Payment;
use App\Entity\TypeOfPayment;
use App\Entity\Wallet;

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
}
