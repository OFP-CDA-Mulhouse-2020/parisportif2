<?php

// Basket - Detroit Pistons
namespace App\DataFixtures\Sports\Basketball\DetroitPistons;

use App\DataFixtures\Sports\Basketball\DetroitPistons\DetroitPistonsTeamFixtures;
use App\DataFixtures\Sports\Basketball\SportBasketballFixtures;
use App\Entity\Player;
use App\Entity\Sport;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DetroitPistonsPlayersFixtures extends Fixture implements DependentFixtureInterface
{
    public function setPlayerData(string $firstName, string $lastName, int $playerStatus, int $ranking): object
    {
        $player = new Player();

        $team = $this->getReference(DetroitPistonsTeamFixtures::TEAM_BASKET_DETROIT_PISTONS);
        $sport = $this->getReference(SportBasketballFixtures::SPORT_BASKETBALL);
        assert($team instanceof Team);
        assert($sport instanceof Sport);

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
        array_push($players, $this->setPlayerData('Wayne', 'Ellington', 1, 73));    // SG
        array_push($players, $this->setPlayerData('Rodney', 'McGruder', 1, 74));
        array_push($players, $this->setPlayerData('Deividas', 'Sirvydis', 1, 69));  // SF
        array_push($players, $this->setPlayerData('Josh', 'Jackson', 1, 76));
        array_push($players, $this->setPlayerData('Stanislas', 'Mykhailiuk', 1, 72));
        array_push($players, $this->setPlayerData('Frank', 'Jackson', 1, 71));      // PG
        array_push($players, $this->setPlayerData('Derrick', 'Rose', 1, 82));
        array_push($players, $this->setPlayerData('Delon', 'Wright', 1, 74));
        array_push($players, $this->setPlayerData('Sekou', 'Doumbouya', 1, 72));    // PF
        array_push($players, $this->setPlayerData('Blake', 'Griffin', 1, 80));
        array_push($players, $this->setPlayerData('Mason', 'Plumlee', 1, 75));      // C
        array_push($players, $this->setPlayerData('Jahlil', 'Okafor', 1, 73));



        foreach ($players as $player) {
            $manager->persist($player);
            $manager->flush();
        }
    }

    public function getDependencies(): array
    {
        return [
            DetroitPistonsTeamFixtures::class,
            SportBasketballFixtures::class,
        ];
    }
}
