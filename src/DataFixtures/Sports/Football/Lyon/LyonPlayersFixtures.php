<?php

// Football - l'Olympique Lyonnais
namespace App\DataFixtures\Sports\Football\Lyon;

use App\DataFixtures\Sports\Football\SportFootballFixtures;
use App\Entity\Player;
use App\Entity\Sport;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LyonPlayersFixtures extends Fixture implements DependentFixtureInterface
{
    public function setPlayerData(string $firstName, string $lastName, int $playerStatus, int $ranking): object
    {
        $player = new Player();

        $team = $this->getReference(LyonTeamFixtures::TEAM_FOOTBALL_OL_LYON);
        $sport = $this->getReference(SportFootballFixtures::SPORT_FOOTBALL);
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
        array_push($players, $this->setPlayerData('Memphis', 'Depay', 1, 87));    // Attaquants
        array_push($players, $this->setPlayerData('Karl', 'Toko-Ekambi', 1, 84));
        array_push($players, $this->setPlayerData('Maxwel', 'Cornet', 2, 75));
        array_push($players, $this->setPlayerData('Tino', 'Kadewere', 2, 74));
        array_push($players, $this->setPlayerData('Houssem', 'Anouar', 1, 86));   // Milieux
        array_push($players, $this->setPlayerData('Lucas', 'Paqueta', 1, 81));
        array_push($players, $this->setPlayerData('Bruno', 'Guimaraes', 1, 78));
        array_push($players, $this->setPlayerData('Thiago', 'Mendes', 1, 77));
        array_push($players, $this->setPlayerData('Maxence', 'Caqueret', 2, 75));
        array_push($players, $this->setPlayerData('Rayan', 'Cherki', 2, 67));
        array_push($players, $this->setPlayerData('Jason', 'Denayer', 1, 84));   // Défenseurs
        array_push($players, $this->setPlayerData('Marcelo', 'Filho', 1, 81));
        array_push($players, $this->setPlayerData('Léo', 'Dubois', 1, 79));
        array_push($players, $this->setPlayerData('Mattia', 'De Sciglio', 1, 78));
        array_push($players, $this->setPlayerData('Melvin', 'Bard', 2, 67));
        array_push($players, $this->setPlayerData('Sinaly', 'Diomandé', 2, 64));
        array_push($players, $this->setPlayerData('Anthony', 'Lopez', 1, 86));  // Gardiens
        array_push($players, $this->setPlayerData('Julian', 'Pollersbeck', 2, 71));


        foreach ($players as $player) {
            $manager->persist($player);
            $manager->flush();
        }
    }

    public function getDependencies(): array
    {
        return [
            LyonTeamFixtures::class,
            SportFootballFixtures::class,
        ];
    }
}
