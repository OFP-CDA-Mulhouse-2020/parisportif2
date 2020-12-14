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
        $order = new Order();
        $this->assertInstanceOf(Order::class, $order);
        $this->assertClassHasAttribute('id', Order::class);
        $this->assertClassHasAttribute('betId', Order::class);
        $this->assertClassHasAttribute('recordedOdds', Order::class);
        $this->assertClassHasAttribute('amount', Order::class);
        $this->assertClassHasAttribute('orderAt', Order::class);
        $this->assertClassHasAttribute('orderStatus', Order::class);
    }

    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(Order $order, $groups): int
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
    public function testValidOrder(int $betId, int $recordedOdds, int $amount, int $expectedViolationsCount): void
    {
        $order = new Order();
        $order->placeAnOrder($betId, $recordedOdds, $amount);
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($order, null));
    }

    public function validOrderProvider(): array
    {
        return [
            [2, 123, 5, 0],
            [23, 150, 5, 0],
        ];
    }

    /**
     * @dataProvider invalidOrderProvider
     */
    public function testInvalidOrder(int $betId, int $recordedOdds, int $amount, int $expectedViolationsCount): void
    {
        $order = new Order();
        $order->placeAnOrder($betId, $recordedOdds, $amount);
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($order, null));
        $this->assertSame($betId, $order->getBetId());
        $this->assertSame($recordedOdds, $order->getRecordedOdds());
        $this->assertSame($amount, $order->getAmount());
        $this->assertEqualsWithDelta(new DateTime(), $order->getOrderAt(), 1);
        $this->assertSame(0, $order->getOrderStatus());
    }

    public function invalidOrderProvider(): array
    {
        return [
            [-2, 123, 5, 1],
            [23, -150, -5, 2],
        ];
    }

    /**
     * @dataProvider validOrderStatusProvider
     */
    public function testValidSetOrderStatus(int $orderStatus): void
    {
        $order = new Order();
        $order->setOrderStatus($orderStatus);
        $this->assertSame(0, $this->getViolationsCount($order, ['orderStatus']));
        $this->assertSame($orderStatus, $order->getOrderStatus());
    }

    public function validOrderStatusProvider(): array
    {
        return [
            [0],
            [1],
            [2],
            [3],
            [4],
        ];
    }

    /**
     * @dataProvider invalidOrderStatusProvider
     */
    public function testInvalidSetOrderStatus(int $orderStatus): void
    {
        $order = new Order();
        $order->setOrderStatus($orderStatus);

        $this->assertSame(1, $this->getViolationsCount($order, ['orderStatus']));
    }

    public function invalidOrderStatusProvider(): array
    {
        return [
            [-1],
            [12],
            [5],
        ];
    }

    /**
     * @dataProvider validCalculateProfitsProvider
     */
    public function testValidCalculateProfits(int $orderStatus, ?int $expectedValue)
    {
        $order = new Order();
        $order->placeAnOrder(1, 222, 1000);
        $order->setOrderStatus($orderStatus);

        $profits = $order->calculateProfits();
        $this->assertSame($expectedValue, $profits);
    }

    public function validCalculateProfitsProvider(): array
    {
        return [
            [0, null],
            [1, 1000],
            [2,(222 * 1000) / 100],
            [3, null],
            [4, 1000],

        ];
    }
}
