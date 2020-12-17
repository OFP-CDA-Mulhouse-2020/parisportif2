<?php

namespace App\Tests;

use App\Entity\PaymentStatus;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class PaymentStatusTest extends KernelTestCase
{
    public function testAssertInstancePaymentStatus(): void
    {
        $paymentStatus = new PaymentStatus();
        $this->assertInstanceOf(PaymentStatus::class, $paymentStatus);
        $this->assertClassHasAttribute('id', PaymentStatus::class);
        $this->assertClassHasAttribute('paymentStatus', PaymentStatus::class);
    }



    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(PaymentStatus $paymentStatus, $groups): int
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($paymentStatus, null, $groups);
        return count($violationList);
    }


    /**
     * @dataProvider validPaymentStatusProvider
     */
    public function testValidPaymentStatus(string $status, int $expectedViolationsCount): void
    {
        $paymentStatus = new PaymentStatus();
        $paymentStatus->setPaymentStatus($status);
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($paymentStatus, null));
    }

    public function validPaymentStatusProvider(): array
    {
        return [
            ['en attente', 0],
            ['Paiement refusé', 0],
            ['Paiement accepté', 0],
        ];
    }


    /**
     * @dataProvider invalidPaymentStatusProvider
     */
    public function testInvalidOrder(string $status, int $expectedViolationsCount): void
    {
        $paymentStatus = new PaymentStatus();
        $paymentStatus->setPaymentStatus($status);
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($paymentStatus, null));
    }

    public function invalidPaymentStatusProvider(): array
    {
        return [
            ['', 1],
            ['1', 1],
        ];
    }
}
