<?php

namespace App\Tests\Unit;

use App\Entity\Payment;
use App\Entity\TypeOfPayment;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class PaymentTest extends KernelTestCase
{

    public function testPaymentConstruct(): void
    {
        $payment = new Payment(50.0);
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertSame(50.0, $payment->getSum());
    }

    public function testPaymentInstance(): void
    {
        $payment = new Payment(50.0);
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertClassHasAttribute('paymentName', Payment::class);
        $this->assertClassHasAttribute('datePayment', Payment::class);
        $this->assertClassHasAttribute('sum', Payment::class);
        $this->assertClassHasAttribute('paymentStatusId', Payment::class);
        $this->assertClassHasAttribute('typeOfPayment', Payment::class);
    }

    /******************************** kernel ****************************** */

    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(Payment $payment, array $groups): int
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
    public function testValidPaymentName(string $paymentName, array $groups, int $numberOfViolations): void
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
    public function testInvalidPaymentName(string $paymentName, array $groups, int $numberOfViolations): void
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

    public function testValidPaymentDate(): void
    {
        $payment = new Payment(50.0);
        $dateActual = new DateTime();
        $this->assertEqualsWithDelta($dateActual, $payment->getDatePayment(), 1);
    }

    /******************************** amount ****************************** */

    /**
     * @dataProvider generateValidSum
     */
    public function testValidSum(float $sum, array $groups, int $numberOfViolations): void
    {
        $payment = new Payment($sum);

        $this->assertSame($numberOfViolations, $this->getViolationsCount($payment, $groups));
    }

    public function generateValidSum(): array
    {
        return [
            [0.5, ['sum'], 0],
            [1000000, ['sum'], 0],
            [5000, ['sum'], 0],
        ];
    }

    /**
     * @dataProvider generateInValidSum
     */
    public function testInValidSum(float $sum, array $groups, int $numberOfViolations): void
    {
        $payment = new Payment($sum);

        $this->assertSame($numberOfViolations, $this->getViolationsCount($payment, $groups));
    }

    public function generateInValidSum(): array
    {
        return [
            [0, ['sum'], 1], // Le montant ne peut pas être égale à 0
            [-1, ['sum'], 1],
        ];
    }

    /******************************** paymentStatus ****************************** */

    // Le statut du paiement :
    // 0 : onGo / En cours;
    // 1 : refuse / Refusé;
    // 2 : accept/ Accepté;

    public function testValidPaymentStatus(): void
    {
        $payment = new Payment(50.0);
        $this->assertSame(0, $this->getViolationsCount($payment, ['paymentStatus']));
        $this->assertSame(0, $payment->getPaymentStatusId());

        $payment->onGoPayment();
        $this->assertSame(0, $payment->getPaymentStatusId());

        $payment->refusePayment();
        $this->assertSame(1, $payment->getPaymentStatusId());

        $payment->acceptPayment();
        $this->assertSame(2, $payment->getPaymentStatusId());
    }

    public function testValidTypeOfPayment(): void
    {
        $payment = new Payment(50.0);

        $typeOfPayment = new TypeOfPayment();
        $typeOfPayment->setTypeOfPayment('Virtuel');
        $payment->setTypeOfPayment($typeOfPayment);

        $this->assertInstanceOf(TypeOfPayment::class, $payment->getTypeOfPayment());
        $this->assertSame(0, $this->getViolationsCount($payment, ['Default']));
    }

    public function testInvalidTypeOfPayment(): void
    {
        $payment = new Payment(50.0);

        $typeOfPayment = new TypeOfPayment();
        $typeOfPayment->setTypeOfPayment('');
        $payment->setTypeOfPayment($typeOfPayment);

        $this->assertSame(1, $this->getViolationsCount($payment, ['Default']));
    }
}
