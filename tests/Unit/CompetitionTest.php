<?php

namespace App\Tests\Unit;

use App\Entity\Competition;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class CompetitionTest extends KernelTestCase
{
    public function testAssertInstanceOfCompetition(): void
    {
        $competition = new Competition();
        $this->assertInstanceOf(Competition::class, $competition);
        $this->assertClassHasAttribute('id', Competition::class);
        $this->assertClassHasAttribute('name', Competition::class);
        $this->assertClassHasAttribute('startAt', Competition::class);
        $this->assertClassHasAttribute('endAt', Competition::class);
    }

    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(Competition $competition, $groups): int
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($competition, null, $groups);
        //var_dump($violationList);
        return count($violationList);
    }

    /**
     * @dataProvider validCompetitionProvider
     */
    public function testValidCompetition(Competition $competition, int $expectedViolationsCount): void
    {
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($competition, null));
    }

    public function validCompetitionProvider(): array
    {
        return [
            [Competition::build('Ligue 1 2020-2021', '2020-08-14', '2021-06-30'), 0],
            [Competition::build('Jeux Olympiques de Toronto 2020', '2020-08-14', '2021-06-30'), 0],
        ];
    }

    /**
     * @dataProvider invalidCompetitionProvider
     */
    public function testInvalidCompetition(Competition $competition, int $expectedViolationsCount): void
    {
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($competition, null));
    }

    public function invalidCompetitionProvider(): array
    {
        return [
            [Competition::build('L', '2020-08-14', '2021-06-30'), 1],
            [Competition::build('Jeux Olympiques de Toronto 2020', '', ''), 2],
            [Competition::build('Jeux Olympiques de Toronto 2020', '2021-05-30', '2021-05-30'), 1],
            [Competition::build('Jeux Olympiques de Toronto 2020', '2022-08-14', '2021-06-30'), 1],
        ];
    }
}
