<?php

namespace App\Tests\Unit;

use App\Entity\Bet;
use App\Entity\Cart;
use App\Entity\Item;
use App\Entity\Payment;
use App\Entity\User;
use App\Entity\Wallet;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CartTest extends KernelTestCase
{
    public function testAssertInstanceOfCart(): void
    {
        $cart = new Cart();
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertClassHasAttribute('id', Cart::class);
        $this->assertClassHasAttribute('sum', Cart::class);
    }

    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(Cart $cart, ?array $groups): int
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        assert($validator instanceof ValidatorInterface);
        $violationList = $validator->validate($cart, null, $groups);
        //var_dump($violationList);
        return count($violationList);
    }

    public function testAddItemToCart(): void
    {
        $cart = new Cart();
        $item = new Item(new Bet());
        $item2 = new Item(new Bet());
        $item3 = new Item(new Bet());

        $cart->addItem($item);
        $cart->addItem($item2);
        $cart->addItem($item3);

        $items = $cart->getItems();

        $this->assertEquals(3, count($items));
    }

    public function testRemoveItemFromCart(): void
    {
        $cart = new Cart();
        $item = new Item(new Bet());
        $item2 = new Item(new Bet());
        $item3 = new Item(new Bet());

        $cart->addItem($item);
        $cart->addItem($item2);
        $cart->addItem($item3);

        $items = $cart->getItems();
        $this->assertEquals(3, count($items));

        assert($items[2] instanceof Item);
        $cart->removeItem($items[2]);
        $items = $cart->getItems();

        $this->assertEquals(2, count($items));
    }

    public function testSumWhenAddToCart(): void
    {
        $cart = new Cart();
        $item = new Item(new Bet());
        $item->isModifiedAmount(50);

        $cart->addItem($item);

        $cart->setSum();
        $sum = $cart->getSum();

        $this->assertSame(50.00, $sum);

        $item2 = new Item(new Bet());
        $item2->isModifiedAmount(20);

        $cart->addItem($item2);

        $cart->setSum();
        $sum = $cart->getSum();

        $this->assertSame(70.00, $sum);
    }

    public function testSumWhenRemoveFromCart(): void
    {
        $cart = new Cart();
        $item = new Item(new Bet());
        $item->isModifiedAmount(50);
        $cart->addItem($item);

        $item2 = new Item(new Bet());
        $item2->isModifiedAmount(20);
        $cart->addItem($item2);

        $cart->setSum();
        $sum = $cart->getSum();
        $this->assertSame(70.00, $sum);

        $cart->removeItem($item);

        $cart->setSum();
        $sum = $cart->getSum();
        $this->assertSame(20.00, $sum);
    }

    public function testSetUser(): void
    {
        $cart = new Cart();
        $user = new User();
        $user->setCart($cart);
        $cart->setUser($user);
        $this->assertInstanceOf(User::class, $cart->getUser());
    }
}
