<?php

namespace App\DataFixtures\Sports\Basketball;

use App\Entity\Sport;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SportBasketballFixtures extends Fixture
{
    public const SPORT_BASKETBALL = 'sport_basketball';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $basketball = new Sport();
        $basketball->setName('Basketball')
            ->setNbOfTeams(2)
            ->setNbOfPlayers(5)
            ->setNbOfSubstitutePlayers(8);

        $manager->persist($basketball);
        $manager->flush();

        $this->addReference(self::SPORT_BASKETBALL, $basketball);
    }
}
