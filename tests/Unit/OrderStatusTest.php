<?php

namespace App\Tests\Unit;

use App\Entity\OrderStatus;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class OrderStatusTest extends KernelTestCase
{
    public function testAssertInstanceOfOrderStatus(): void
    {
        $orderStatus = new OrderStatus();
        $this->assertInstanceOf(OrderStatus::class, $orderStatus);
        $this->assertClassHasAttribute('id', OrderStatus::class);
        $this->assertClassHasAttribute('status', OrderStatus::class);
    }

    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(OrderStatus $orderStatus, $groups): int
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($orderStatus, null, $groups);
        //var_dump($violationList);
        return count($violationList);
    }

    /**
     * @dataProvider validOrderStatusProvider
     */
    public function testValidOrder(string $status, int $expectedViolationsCount): void
    {
        $orderStatus = new OrderStatus();
        $orderStatus->setStatus($status);
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($orderStatus, null));
    }

    public function validOrderStatusProvider(): array
    {
        return [
            ['en attente', 0],
            ['paiement rejeté de 0', 0],
        ];
    }

    /**
     * @dataProvider invalidOrderStatusProvider
     */
    public function testInvalidOrder(string $status, int $expectedViolationsCount): void
    {
        $orderStatus = new OrderStatus();
        $orderStatus->setStatus($status);
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($orderStatus, null));
    }

    public function invalidOrderStatusProvider(): array
    {
        return [
            ['', 1],
            ['1', 1],
        ];
    }
}
