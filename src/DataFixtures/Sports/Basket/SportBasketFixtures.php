<?php

namespace App\DataFixtures\Sports\Basket;

use App\Entity\Sport;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SportBasketFixtures extends Fixture
{
    public const SPORT_BASKET = 'sport_basket';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $basket = new Sport();
        $basket->setName('Basket')
            ->setNbOfTeams(2)
            ->setNbOfPlayers(5)
            ->setNbOfSubstitutePlayers(8);

        $manager->persist($basket);
        $manager->flush();

        $this->addReference(self::SPORT_BASKET, $basket);
    }
}
