<?php

namespace App\Tests\Unit;

use App\Entity\Cart;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class CartTest extends KernelTestCase
{
    public function testAssertInstanceOfCart(): void
    {
        $cart = new Cart();
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertClassHasAttribute('id', Cart::class);
        $this->assertClassHasAttribute('sum', Cart::class);
    }
}
