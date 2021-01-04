<?php

namespace App\Tests;

use App\Entity\Player;
use App\Entity\Sport;
use App\Entity\Team;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class SportTest extends KernelTestCase
{
    public function testAssertInstanceOfSport(): void
    {
        $sport = new Sport();
        $this->assertInstanceOf(Sport::class, $sport);
        $this->assertClassHasAttribute('id', Sport::class);
        $this->assertClassHasAttribute('name', Sport::class);
        $this->assertClassHasAttribute('nbOfTeams', Sport::class);
        $this->assertClassHasAttribute('nbOfPlayers', Sport::class);
        $this->assertClassHasAttribute('nbOfSubstitutePlayers', Sport::class);
    }


    /******************* kernel *******************/
    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(Sport $sport, ?array $groups): int
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($sport, $groups);

        return count($violationList);
    }



    /**
     * @dataProvider validSportDataProvider
     */
    public function testValidSport(Sport $sport, int $expectedViolationsCount): void
    {
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($sport, null));
    }

    public function validSportDataProvider(): array
    {
        return [
            [Sport::build('team', 1, 3, 1), 0],
            [Sport::build('Tema', 1, 1, 3), 0],

        ];
    }


    /**
     * @dataProvider invalidSportDataProvider
     */
    public function testInvalidSport(Sport $port, int $expectedViolationsCount): void
    {
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($port, null));
    }

    public function invalidSportDataProvider(): array
    {
        return [
            [Sport::build('Z', 1, 1, 1), 1],
            [Sport::build('Team', -1, 1, 1), 1],
            [Sport::build('Team', 1, -1, 1), 1],
            [Sport::build('Team', 1, 1, -1), 1],


        ];
    }
}
