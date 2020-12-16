<?php

namespace App\Tests;

use App\Entity\PaymentStatus;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PaymentStatusTest extends KernelTestCase
{
    public function testAssertInstancePaymentStatus(): void
    {
        $paymentStatus = new PaymentStatus();
        $this->assertInstanceOf(PaymentStatus::class, $paymentStatus);
        $this->assertClassHasAttribute('id', PaymentStatus::class);
        $this->assertClassHasAttribute('paymentStatus', PaymentStatus::class);
    }

    public function testValidPaymentStatus()
    {
        $paymentStatus = new PaymentStatus();
        $this->assertSame('Paiement en cours', $paymentStatus->getPaymentStatus());

        $paymentStatus->onGoPayment();
        $this->assertSame('Paiement en cours', $paymentStatus->getPaymentStatus());

        $paymentStatus->refusePayment();
        $this->assertSame('Paiement refusé', $paymentStatus->getPaymentStatus());

        $paymentStatus->acceptPayment();
        $this->assertSame('Paiement accepté', $paymentStatus->getPaymentStatus());
    }
}
