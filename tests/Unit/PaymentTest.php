<?php

namespace App\Tests;

use App\Entity\Payment;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class PaymentTest extends KernelTestCase
{


    public function testPaymentConstruct()
    {
        $payment = new Payment(50.0);
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertSame(50.0, $payment->getAmount());
    }



    public function testPaymentInstance()
    {
        $payment = new Payment(50.0);
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertClassHasAttribute('paymentName', Payment::class);
        $this->assertClassHasAttribute('datePayment', Payment::class);
        $this->assertClassHasAttribute('amount', Payment::class);
    }



    /******************************** kernel ****************************** */

    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(Payment $payment, $groups): int
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($payment, null, $groups);

        return count($violationList);
    }




    /******************************** paymentName ****************************** */

    /**
     * @dataProvider generateValidPaymentName
     */

    public function testValidPaymentName($paymentName, $groups, $numberOfViolations)
    {
        $payment = new Payment(50.0);
        $payment->setPaymentName($paymentName);
        $this->assertSame($numberOfViolations, $this->getViolationsCount($payment, $groups));
    }

    public function generateValidPaymentName(): array
    {
        return [
            ['test', ['paymentName'], 0]
        ];
    }



    /**
     * @dataProvider generateInvalidPaymentName
     */

    public function testInvalidPaymentName($paymentName, $groups, $numberOfViolations)
    {
        $payment = new Payment(50.0);
        $payment->setPaymentName($paymentName);
        $this->assertSame($numberOfViolations, $this->getViolationsCount($payment, $groups));
    }


    public function generateInvalidPaymentName(): array
    {
        return [
            ['', ['paymentName'], 1],
            ['111hfdkjhsf', ['paymentName'], 1]
        ];
    }



    /******************************** datePayment ****************************** */

    // /**
    //  * @dataProvider generateValidPaymentDate
    //  */

    public function testValidPaymentDate(): void
    {
        $payment = new Payment(50.0);
        $dateActual = new DateTime();
        $this->assertEqualsWithDelta($dateActual, $payment->getDatePayment(), 1);
    }


    /******************************** amount ****************************** */

    /**
     * @dataProvider generateValidAmount
     */

    public function testValidAmount(float $amount, $groups, $numberOfViolations)
    {
        $payment = new Payment($amount);

        $this->assertSame($numberOfViolations, $this->getViolationsCount($payment, $groups));
    }


    public function generateValidAmount(): array
    {
        return [
            [0.5, ['amount'], 0],
            [1000000, ['amount'], 0],
            [5000, ['amount'], 0],

        ];
    }


    /**
     * @dataProvider generateInValidAmount
     */

    public function testInValidAmount(float $amount, $groups, $numberOfViolations)
    {
        $payment = new Payment($amount);

        $this->assertSame($numberOfViolations, $this->getViolationsCount($payment, $groups));
    }


    public function generateInValidAmount(): array
    {
        return [
            [0, ['amount'], 1], // Le montant ne peut pas être égale à 0
            [-1, ['amount'], 1],



        ];
    }




    /******************************** paymentStatus ****************************** */

    // Le statut du paiement :
    // 1 : onGo / En cours;
    // 2 : refuse / Refusé;
    // 3 : accept/ Accepté;


    public function testValidPaymentStatus()
    {
        $payment = new payment(50.0);
        $this->assertSame(1, $payment->getPaymentStatus());

        $payment->onGoPayment();
        $this->assertSame(1, $payment->getPaymentStatus());

        $payment->refusePayment();
        $this->assertSame(2, $payment->getPaymentStatus());

        $payment->acceptPayment();
        $this->assertSame(3, $payment->getPaymentStatus());
    }
}
