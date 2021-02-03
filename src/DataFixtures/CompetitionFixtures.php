<?php

namespace App\DataFixtures;

use App\DataFixtures\Sports\Basket\SportBasketFixtures;
use App\DataFixtures\Sports\Football\SportFootballFixtures;
use App\Entity\Competition;
use App\Entity\Sport;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompetitionFixtures extends Fixture
{
    public const COMPETITION_LIGUE_1 = 'compétition Lique 1';
    public const COMPETITION_NBA = 'compétition NBA';

    public function setCompetitionData(string $name, string $startAt, string $endAt, Sport $sport): object
    {
        $competition = new Competition();

        $competition->setName($name)
            ->setStartAt(new DateTime($startAt))
            ->setEndAt(new DateTime($endAt))
            ->setSport($sport);

        return $competition;
    }

    public function load(ObjectManager $manager): void
    {
        $sport1 = $this->getReference(SportFootballFixtures::SPORT_FOOTBALL);
        $sport2 = $this->getReference(SportBasketFixtures::SPORT_BASKET);
        assert($sport1 instanceof Sport);
        assert($sport2 instanceof Sport);

        $competition1 = $this->setCompetitionData(
            'Ligue 1 Ubereats',
            '2020-09-01 00:00:00',
            '2021-06-30 23:59:59',
            $sport1
        );
        $competition2 = $this->setCompetitionData(
            'NBA',
            '2020-10-01 00:00:00',
            '2021-07-31 23:59:59',
            $sport2
        );


        $manager->persist($competition1);
        $manager->persist($competition2);
        $manager->flush();
        $this->addReference(self::COMPETITION_LIGUE_1, $competition1);
        $this->addReference(self::COMPETITION_NBA, $competition2);
    }
}
