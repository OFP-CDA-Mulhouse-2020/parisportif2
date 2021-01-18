<?php

namespace App\DataFixtures\Sports\Football\Strasbourg;

use App\DataFixtures\Sports\Football\SportFootballFixtures;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StrasbourgTeamFixtures extends Fixture implements DependentFixtureInterface
{
    public const TEAM_FOOTBALL_RCS_ALSACE = 'racing_club_strasbourg';

    public function load(ObjectManager $manager): void
    {
        $sport = $this->getReference(SportFootballFixtures::SPORT_FOOTBALL);

        $team = new Team();
        $team->setName('Racing Club de Strasbourg Alsace')
            ->setRanking(72)
            ->setSport($sport);

        $manager->persist($team);
        $manager->flush();

        $this->addReference(self::TEAM_FOOTBALL_RCS_ALSACE, $team);
    }


    public function getDependencies(): array
    {
        return [
            SportFootballFixtures::class,

        ];
    }
}
