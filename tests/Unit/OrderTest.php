<?php

namespace App\Tests\Unit;

use App\Entity\Order;
use App\Entity\Wallet;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class OrderTest extends KernelTestCase
{
    public function testAssertInstanceOfOrder()
    {
        $order = new Order();
        $this->assertInstanceOf(Order::class, $order);
        $this->assertClassHasAttribute('id', Order::class);
        $this->assertClassHasAttribute('betId', Order::class);
        $this->assertClassHasAttribute('recordedOdd', Order::class);
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
  /*  public function testValidOrder()
    {
        $order = new Order();
        $order->placeAnOrder($betId, $recordedOdd, $amount);
        $this->assertSame(0, $this->getViolationsCount($order, null));
    }

    public function validOrderProvider(): array
    {
        return [
            [2, 123, 5, ['null'], 0],
            [23, 150, 5, ['null'], 0],
        ];
    }

*/
}
