<?php

namespace App\Tests\Unit;

use App\Entity\Bet;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class BetTest extends KernelTestCase
{
    public function testAssertInstanceOfOBet(): void
    {
        $bet = new Bet();
        $this->assertInstanceOf(Bet::class, $bet);
        $this->assertClassHasAttribute('id', Bet::class);
        $this->assertClassHasAttribute('listOfOdds', Bet::class);
        $this->assertClassHasAttribute('betLimitTime', Bet::class);
        $this->assertClassHasAttribute('typeOfBetId', Bet::class);
        $this->assertClassHasAttribute('betOpened', Bet::class);
    }

    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(Bet $bet, $groups): int
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($bet, null, $groups);
        //var_dump($violationList);
        return count($violationList);
    }

    /**
     * @dataProvider validBetProvider
     * @param Bet $bet
     * @param array|null $groups
     * @param int $expectedViolationsCount
     */
    public function testValidBet(Bet $bet, ?array $groups, int $expectedViolationsCount): void
    {
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($bet, $groups));
    }

    public function validBetProvider(): array
    {
        return [
            [Bet::build('2002-12-12', [
                0 => ['V1' => 1.2],
                1 => ['N' => 2.2],
                2 => ['V2' => 3.2],
            ], 0), null , 0],
            [Bet::build('2022-12-12', null, 5), ['datetime'], 0],
            [Bet::build('2022-12-12', [
                0 => ['V1' => 1.2],
                1 => ['N' => 2.2],
                2 => ['V2' => 3.2],
            ], 3), null, 0],

        ];
    }

    /**
     * @dataProvider invalidBetProvider
     * @param Bet $bet
     * @param array|null $groups
     * @param int $expectedViolationsCount
     */
    public function testInvalidBet(Bet $bet, ?array $groups, int $expectedViolationsCount): void
    {
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($bet, $groups));
    }

    public function invalidBetProvider(): array
    {
        return [
            [Bet::build('2022-12-12', [
                0 => ['V1' => 1.2],
                1 => ['N' => 2.2],
                2 => ['V2' => 3.2],
            ], 0), null , 0],
            [Bet::build('2022-12-12', null, 5), ['datetime'], 0],
            [Bet::build('2022-12-12', [
                0 => ['V1' => 1.2],
                1 => ['N' => 2.2],
                2 => ['V2' => 3.2],
            ], 3), null, 0],

        ];
    }
}
