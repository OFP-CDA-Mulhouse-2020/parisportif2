<?php

namespace App\Tests\Unit;

use App\Entity\OrderStatus;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class OrderStatusTest extends KernelTestCase
{
    public function testAssertInstanceOfOrderStatus(): void
    {
        $order = new OrderStatus();
        $this->assertInstanceOf(OrderStatus::class, $order);
        $this->assertClassHasAttribute('id', OrderStatus::class);
        $this->assertClassHasAttribute('status', OrderStatus::class);
    }
}
