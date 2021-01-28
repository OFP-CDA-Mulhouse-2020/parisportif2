<?php

namespace App\Tests\Unit;

use App\Entity\Player;
use App\Entity\Sport;
use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TeamTest extends KernelTestCase
{
    public function testAssertInstanceOfTeam(): void
    {
        $team = new Team();
        $this->assertInstanceOf(Team::class, $team);
        $this->assertClassHasAttribute('id', Team::class);
        $this->assertClassHasAttribute('name', Team::class);
        $this->assertClassHasAttribute('ranking', Team::class);
        $this->assertClassHasAttribute('player', Team::class);
        $this->assertClassHasAttribute('sport', Team::class);
        $this->assertClassHasAttribute('event', Team::class);
    }

    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(Team $data, ?array $groups): int
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        assert($validator instanceof ValidatorInterface);
        $violationList = $validator->validate($data, null, $groups);
        return count($violationList);
    }


    /**
     * @dataProvider validTeamDataProvider
     */
    public function testValidTeam(Team $team, int $expectedViolationsCount): void
    {
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($team, null));
    }

    public function validTeamDataProvider(): array
    {
        return [
            [Team::build('ZTKK', 1), 0],
            [Team::build('XYZ', 3), 0],

        ];
    }


    /**
     * @dataProvider invalidTeamDataProvider
     */
    public function testInvalidTeam(Team $buildData, int $expectedViolationsCount): void
    {
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($buildData, null));
    }

    public function invalidTeamDataProvider(): array
    {
        return [
            [Team::build('Z', null), 1],
            [Team::build(null, -1), 1],


        ];
    }


    public function testIsEnoughPlayers(): void
    {
        $team = new Team();

        $sport = new Sport();
        $sport->setName('football');
        $sport->setNbOfPlayers(2);

        //Relate sport to team
        $team->setSport($sport);

        $player1 = new Player();
        $player2 = new Player();
        $player3 = new Player();
        $player4 = new Player();


        //relate player to team
        $team->addPlayer($player1);
        $team->addPlayer($player2);
        $team->addPlayer($player3);
        $team->addPlayer($player4);

        $this->assertSame(0, $this->getViolationsCount($team, ['isEnoughPlayers']));
    }


    public function testIsNotEnoughPlayers(): void
    {
        $team = new Team();

        $sport = new Sport();
        $sport->setName('football');
        $sport->setNbOfPlayers(3);

        //Relate sport to team
        $team->setSport($sport);

        $player1 = new Player();

        //relate player to team
        $team->addPlayer($player1);

        $this->assertSame(1, $this->getViolationsCount($team, ['isEnoughPlayers']));
    }
}
