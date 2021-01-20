<?php

// Football - Racing Club de Strasbourg
namespace App\DataFixtures\Sports\Football\Strasbourg;

use App\DataFixtures\Sports\Football\SportFootballFixtures;
use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StrasbourgPlayersFixtures extends Fixture implements DependentFixtureInterface
{
    public function setPlayerData(string $firstName, string $lastName, int $playerStatus, int $ranking): object
    {
        $player = new Player();

        $team = $this->getReference(StrasbourgTeamFixtures::TEAM_FOOTBALL_RCS_ALSACE);
        $sport = $this->getReference(SportFootballFixtures::SPORT_FOOTBALL);

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
        array_push($players, $this->setPlayerData('Ludovic', 'Ajorque', 1, 81));    // Attaquants
        array_push($players, $this->setPlayerData('Habib', 'Diallo', 1, 76));
        array_push($players, $this->setPlayerData('Lebo', 'Mothiba', 2, 72));
        array_push($players, $this->setPlayerData('Adrien', 'Thomasson', 1, 77));   // Milieux
        array_push($players, $this->setPlayerData('Dimitri', 'Lienar', 1, 75));
        array_push($players, $this->setPlayerData('Jean-Eudes', 'Aholou', 1, 74));
        array_push($players, $this->setPlayerData('Abdul Majeed', 'Waris', 1, 73));
        array_push($players, $this->setPlayerData('Sanjin', 'pric', 2, 71));
        array_push($players, $this->setPlayerData('Kévin', 'Zohi', 2, 71));
        array_push($players, $this->setPlayerData('Alexander', 'Djiku', 1, 79));   // Défenseurs
        array_push($players, $this->setPlayerData('Stefan', 'Mitrovic', 1, 74));
        array_push($players, $this->setPlayerData('Lamine', 'Koné', 1, 74));
        array_push($players, $this->setPlayerData('Anthony', 'Caci', 1, 74));
        array_push($players, $this->setPlayerData('Lionel', 'Carole', 2, 72));
        array_push($players, $this->setPlayerData('Mohamed', 'Simakan', 2, 71));
        array_push($players, $this->setPlayerData('Matz', 'Sels', 1, 79));        // Gardiens
        array_push($players, $this->setPlayerData('Binguru', 'Kamara', 2, 73));
        array_push($players, $this->setPlayerData('Eiji', 'Kawashima', 2, 68));

        foreach ($players as $player) {
            $manager->persist($player);
            $manager->flush();
        }
    }

    public function getDependencies(): array
    {
        return [
            StrasbourgTeamFixtures::class,
            SportFootballFixtures::class,
        ];
    }
}
