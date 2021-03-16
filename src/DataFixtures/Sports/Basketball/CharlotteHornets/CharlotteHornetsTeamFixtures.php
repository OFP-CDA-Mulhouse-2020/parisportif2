<?php

namespace App\DataFixtures\Sports\Basketball\CharlotteHornets;

use App\DataFixtures\Sports\Basketball\SportBasketballFixtures;
use App\Entity\Sport;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CharlotteHornetsTeamFixtures extends Fixture implements DependentFixtureInterface
{
    public const TEAM_BASKET_CHARLOTTE_HORNETS = 'Charlotte Hornets';

    public function load(ObjectManager $manager): void
    {
        $sport = $this->getReference(SportBasketballFixtures::SPORT_BASKETBALL);
        assert($sport instanceof Sport);

        $team = new Team();
        $team->setName('Charlotte Hornets')
            ->setRanking(76)
            ->setSport($sport);

        $manager->persist($team);
        $manager->flush();

        $this->addReference(self::TEAM_BASKET_CHARLOTTE_HORNETS, $team);
    }


    public function getDependencies(): array
    {
        return [
            SportBasketballFixtures::class,
        ];
    }
}
