<?php

namespace App\DataFixtures\Sports\Football;

use App\Entity\Sport;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SportFootballFixtures extends Fixture
{
    public const SPORT_FOOTBALL = 'sport_football';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $football = new Sport();
        $football->setName('Football')
            ->setNbOfTeams(2)
            ->setNbOfPlayers(11)
            ->setNbOfSubstitutePlayers(7);

        $manager->persist($football);
        $manager->flush();

        $this->addReference(self::SPORT_FOOTBALL, $football);
    }
}
