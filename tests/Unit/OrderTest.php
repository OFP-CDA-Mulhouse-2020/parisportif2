<?php

namespace App\Tests\Unit;

use App\Entity\Order;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class OrderTest extends KernelTestCase
{
    public function testAssertInstanceOfOrder(): void
    {
        $order = new Order(1, 2.22);
        $this->assertInstanceOf(Order::class, $order);
        $this->assertClassHasAttribute('id', Order::class);
        $this->assertClassHasAttribute('betId', Order::class);
        $this->assertClassHasAttribute('recordedOdds', Order::class);
        $this->assertClassHasAttribute('amount', Order::class);
        $this->assertClassHasAttribute('orderAt', Order::class);
        $this->assertClassHasAttribute('orderStatusId', Order::class);
    }

    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(Order $order, ?array $groups): int
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($order, null, $groups);
        //var_dump($violationList);
        return count($violationList);
    }

    /**
     * @dataProvider validOrderProvider
     */
    public function testValidOrder(int $betId, float $recordedOdds, float $amount, int $expectedViolationsCount): void
    {
        $order = new Order($betId, $recordedOdds);
        $order->setAmount($amount);
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($order, null));

        $this->assertSame($betId, $order->getBetId());
        $this->assertSame($recordedOdds, $order->getRecordedOdds());
        $this->assertSame($amount, $order->getAmount());
        $this->assertEqualsWithDelta(new DateTime(), $order->getOrderAt(), 1);
        $this->assertSame(0, $order->getOrderStatusId());
    }

    public function validOrderProvider(): array
    {
        return [
            [2, 1.23, 5.5, 0],
            [23, 2.52, 15, 0],
        ];
    }

    /**
     * @dataProvider invalidOrderProvider
     */
    public function testInvalidOrder(int $betId, float $recordedOdds, float $amount, int $expectedViolationsCount): void
    {
        $order = new Order($betId, $recordedOdds);
        $order->setAmount($amount);
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($order, null));
    }

    public function invalidOrderProvider(): array
    {
        return [
            [-2, 123, 5, 1],
            [2, 0.99, 5.2, 1],
            [23, -150, -5, 2],
        ];
    }

    public function testValidOrderStatus(): void
    {
        $order = new Order(5, 22);
        $this->assertSame(0, $this->getViolationsCount($order, ['orderStatus']));
        $this->assertSame(0, $order->getOrderStatusId());

        $order->payOrder();
        $this->assertSame(1, $order->getOrderStatusId());

        $order->winOrder();
        $this->assertSame(2, $order->getOrderStatusId());

        $order->looseOrder();
        $this->assertSame(3, $order->getOrderStatusId());

        $order->refundOrder();
        $this->assertSame(4, $order->getOrderStatusId());

        $order->closeOrder();
        $this->assertSame(5, $order->getOrderStatusId());
    }

    public function testValidCalculateProfitsForOrderNotPayed(): void
    {
        $order = new Order(1, 2.22);
        $order->setAmount(10);

        $order->calculateProfits();
        $this->assertSame(-10.00, $order->calculateProfits());
    }

    public function testValidCalculateProfitsForOrderPayed(): void
    {
        $order = new Order(1, 2.22);
        $order->setAmount(10);
        $order->payOrder();

        $order->calculateProfits();
        $this->assertSame(null, $order->calculateProfits());
    }

    public function testValidCalculateProfitsForWinOrder(): void
    {
        $order = new Order(1, 2.22);
        $order->setAmount(10);
        $order->winOrder();

        $order->calculateProfits();
        $this->assertSame(22.2, $order->calculateProfits());
    }

    public function testValidCalculateProfitsForLooseOrder(): void
    {
        $order = new Order(1, 2.22);
        $order->setAmount(10);
        $order->looseOrder();

        $order->calculateProfits();
        $this->assertSame(null, $order->calculateProfits());
    }

    public function testValidCalculateProfitsForRefundOrder(): void
    {
        $order = new Order(1, 2.22);
        $order->setAmount(10);
        $order->refundOrder();

        $order->calculateProfits();
        $this->assertSame(10.00, $order->calculateProfits());
    }

    public function testValidCalculateProfitsForCloseOrder(): void
    {
        $order = new Order(1, 2.22);
        $order->setAmount(10);
        $order->closeOrder();

        $order->calculateProfits();
        $this->assertSame(null, $order->calculateProfits());
    }
}
