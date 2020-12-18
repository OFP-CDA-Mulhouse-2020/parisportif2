<?php

namespace App\Tests;

use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class TeamTest extends KernelTestCase
{
    public function testAssertInstanceOfTeam(): void
    {
        $team = new Team();
        $this->assertInstanceOf(Team::class, $team);
        $this->assertClassHasAttribute('id', Team::class);
        $this->assertClassHasAttribute('name', Team::class);
        $this->assertClassHasAttribute('sport', Team::class);
        $this->assertClassHasAttribute('nbPlayer', Team::class);
        $this->assertClassHasAttribute('ranking', Team::class);
    }

    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(Team $data, $groups): int
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
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
            [Team::build('ZTKK', 'Basket', 1, 1), 0],
            [Team::build('XYZ', 'Rugby', 0, 3), 0],

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
            [Team::build('Z', null, null, null), 1],
            [Team::build(null, 'J', null, null), 1],
            [Team::build(null, null, -1, null), 1],
            [Team::build(null, null, null, -1), 1],


        ];
    }
}
