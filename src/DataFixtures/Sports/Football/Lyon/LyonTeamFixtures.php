<?php

namespace App\DataFixtures\Sports\Football\Lyon;

use App\DataFixtures\Sports\Football\SportFootballFixtures;
use App\Entity\Sport;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LyonTeamFixtures extends Fixture implements DependentFixtureInterface
{
    public const TEAM_FOOTBALL_OL_LYON = 'olympique_lyonnais';

    public function load(ObjectManager $manager): void
    {
        $sport = $this->getReference(SportFootballFixtures::SPORT_FOOTBALL);
        assert($sport instanceof Sport);

        $team = new Team();
        $team->setName("l'Olympique Lyonnais")
            ->setRanking(76)
            ->setSport($sport);

        $manager->persist($team);
        $manager->flush();

        $this->addReference(self::TEAM_FOOTBALL_OL_LYON, $team);
    }


    public function getDependencies(): array
    {
        return [
            SportFootballFixtures::class,
        ];
    }
}
