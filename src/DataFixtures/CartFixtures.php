<?php

namespace App\DataFixtures;

use App\Entity\Cart;
use App\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CartFixtures extends Fixture implements DependentFixtureInterface
{
    public const CART_1 = 'cart_1';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $item3 = $this->getReference(ItemFixtures::ITEM_3);
        $item4 = $this->getReference(ItemFixtures::ITEM_4);

        assert($item3 instanceof Item);
        assert($item4 instanceof Item);

        $cart1 = new Cart();
        $cart1->addItem($item3);
        $cart1->addItem($item4);
        $cart1->setSum();
        $manager->persist($cart1);

        $manager->flush();

        $this->addReference(self::CART_1, $cart1);
    }

    public function getDependencies(): array
    {
        return [
            ItemFixtures::class,

        ];
    }
}
