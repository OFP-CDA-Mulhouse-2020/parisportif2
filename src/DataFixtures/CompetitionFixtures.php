<?php

namespace App\DataFixtures;

use App\DataFixtures\Sports\Basketball\SportBasketballFixtures;
use App\DataFixtures\Sports\Football\SportFootballFixtures;
use App\Entity\Competition;
use App\Entity\Sport;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompetitionFixtures extends Fixture
{
    public const COMPETITION_LIGUE_1 = 'compétition Lique 1';
    public const COMPETITION_LIGUE_2 = 'compétition Lique 2';
    public const COMPETITION_CHAMPIONS_LEAGUE = 'compétition Ligue des champions';
    public const COMPETITION_PREMIER_LEAGUE = 'compétition Premier League';
    public const COMPETITION_LALIGA = 'compétition LaLiga';
    public const COMPETITION_SERIE_A = 'compétition Serie A';
    public const COMPETITION_BUNDESLIGA = 'compétition Bundesliga';

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
        $sport2 = $this->getReference(SportBasketballFixtures::SPORT_BASKETBALL);
        assert($sport1 instanceof Sport);
        assert($sport2 instanceof Sport);

        $competition1 = $this->setCompetitionData(
            'Ligue 1 Ubereats',
            '2020-09-01 00:00:00',
            '2021-06-30 23:59:59',
            $sport1
        );

        $competition2 = $this->setCompetitionData(
            'Ligue 2 BKT',
            '2020-09-01 00:00:00',
            '2021-06-30 23:59:59',
            $sport1
        );
        $competition3 = $this->setCompetitionData(
            'Ligue des champions',
            '2020-09-01 00:00:00',
            '2021-06-30 23:59:59',
            $sport1
        );
        $competition4 = $this->setCompetitionData(
            'Premier League',
            '2020-09-01 00:00:00',
            '2021-06-30 23:59:59',
            $sport1
        );

        $competition5 = $this->setCompetitionData(
            'LaLiga',
            '2020-09-01 00:00:00',
            '2021-06-30 23:59:59',
            $sport1
        );

        $competition6 = $this->setCompetitionData(
            'Serie A',
            '2020-09-01 00:00:00',
            '2021-06-30 23:59:59',
            $sport1
        );

        $competition7 = $this->setCompetitionData(
            'Bundesliga',
            '2020-09-01 00:00:00',
            '2021-06-30 23:59:59',
            $sport1
        );

        $competition10 = $this->setCompetitionData(
            'NBA',
            '2020-10-01 00:00:00',
            '2021-07-31 23:59:59',
            $sport2
        );


        $manager->persist($competition1);
        $manager->persist($competition2);
        $manager->persist($competition3);
        $manager->persist($competition4);
        $manager->persist($competition5);
        $manager->persist($competition6);
        $manager->persist($competition7);
        $manager->persist($competition10);
        $manager->flush();

        $this->addReference(self::COMPETITION_LIGUE_1, $competition1);
        $this->addReference(self::COMPETITION_LIGUE_2, $competition2);
        $this->addReference(self::COMPETITION_CHAMPIONS_LEAGUE, $competition3);
        $this->addReference(self::COMPETITION_PREMIER_LEAGUE, $competition4);
        $this->addReference(self::COMPETITION_LALIGA, $competition5);
        $this->addReference(self::COMPETITION_SERIE_A, $competition6);
        $this->addReference(self::COMPETITION_BUNDESLIGA, $competition7);
        $this->addReference(self::COMPETITION_NBA, $competition10);
    }
}
