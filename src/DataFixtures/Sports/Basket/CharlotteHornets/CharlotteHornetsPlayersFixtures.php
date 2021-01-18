<?php

// Basket - CharlotteHornets
namespace App\DataFixtures\Sports\Basket\CharlotteHornets;

use App\DataFixtures\Sports\Basket\CharlotteHornets\CharlotteHornetsTeamFixtures;
use App\DataFixtures\Sports\Basket\SportBasketFixtures;
use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CharlotteHornetsPlayersFixtures extends Fixture implements DependentFixtureInterface
{
    public function setPlayerData(string $firstName, string $lastName, int $playerStatus, int $ranking): object
    {
        $player = new Player();

        $team = $this->getReference(CharlotteHornetsTeamFixtures::TEAM_BASKET_CHARLOTTE_HORNETS);
        $sport = $this->getReference(SportBasketFixtures::SPORT_BASKET);

        $player->setFirstName($firstName)
            ->setLastName($lastName)
            ->setPlayerStatus($playerStatus)
            ->setRanking($ranking)
            ->setTeam($team)
            ->setSport($sport);

        return $player;
    }


    public function load(ObjectManager $manager): void
    {
        $players = array();
        array_push($players, $this->setPlayerData('Malik', 'Monk', 1, 77));     // SG
        array_push($players, $this->setPlayerData('Gordon', 'Hayward', 1, 81)); // SF
        array_push($players, $this->setPlayerData('Miles', 'Bridges', 2, 77));
        array_push($players, $this->setPlayerData('Caleb', 'Martin', 2, 66));   // PG
        array_push($players, $this->setPlayerData('Terry', 'Rozier', 1, 81));
        array_push($players, $this->setPlayerData('Devonte', 'Graham', 2, 81));
        array_push($players, $this->setPlayerData('Jalen', 'McDaniels', 1, 71)); // PF
        array_push($players, $this->setPlayerData('PJ', 'Washington', 2, 74));
        array_push($players, $this->setPlayerData('Cody', 'Martin', 2, 74));
        array_push($players, $this->setPlayerData('Cody', 'Zeller', 1, 80));    // C
        array_push($players, $this->setPlayerData('Bismack', 'Biyombo', 2, 75));




        foreach ($players as $player) {
            $manager->persist($player);
            $manager->flush();
        }
    }

    public function getDependencies(): array
    {
        return [
            CharlotteHornetsTeamFixtures::class,
            SportBasketFixtures::class,
        ];
    }
}
