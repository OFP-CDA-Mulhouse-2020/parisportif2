<?php

namespace App\DataFixtures\Sports\Basketball\DetroitPistons;

use App\DataFixtures\Sports\Basketball\SportBasketballFixtures;
use App\Entity\Sport;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DetroitPistonsTeamFixtures extends Fixture implements DependentFixtureInterface
{
    public const TEAM_BASKET_DETROIT_PISTONS = 'Detroit Pistons';

    public function load(ObjectManager $manager): void
    {
        $sport = $this->getReference(SportBasketballFixtures::SPORT_BASKETBALL);
        assert($sport instanceof Sport);

        $team = new Team();
        $team->setName('Detroit Pistons')
            ->setRanking(76)
            ->setSport($sport);

        $manager->persist($team);
        $manager->flush();

        $this->addReference(self::TEAM_BASKET_DETROIT_PISTONS, $team);
    }


    public function getDependencies(): array
    {
        return [
            SportBasketballFixtures::class,
        ];
    }
}
