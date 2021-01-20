<?php

namespace App\DataFixtures;

use App\Entity\Competition;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompetitionFixtures extends Fixture
{
    public const COMPETITION_LIGUE_1 = 'compétition Lique 1';
    public const COMPETITION_NBA = 'compétition NBA';

    public function setCompetitionData(string $name, string $startAt, string $endAt): object
    {
        $competition = new Competition();

        $competition->setName($name)
            ->setStartAt(new DateTime($startAt))
            ->setEndAt(new DateTime($endAt));


        return $competition;
    }

    public function load(ObjectManager $manager): void
    {

        $competitions1 = array();

        array_push(
            $competitions1,
            $this->setCompetitionData('championnat Ligue 1', '2020-09-01 00:00:00', '2021-06-30 23:59:59')
        );

        foreach ($competitions1 as $competition1) {
            $manager->persist($competition1);
            $manager->flush();
        }


        $competitions2 = array();

        array_push(
            $competitions2,
            $this->setCompetitionData('championnat NBA', '2020-10-01 00:00:00', '2021-07-31 23:59:59')
        );

        foreach ($competitions2 as $competition2) {
            $manager->persist($competition2);
            $manager->flush();
        }

        $this->addReference(self::COMPETITION_LIGUE_1, $competition1);
        $this->addReference(self::COMPETITION_NBA, $competition2);
    }
}
