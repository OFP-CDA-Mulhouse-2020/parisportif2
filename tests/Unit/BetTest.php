<?php

namespace App\Tests\Unit;

use App\Entity\Bet;
use App\Entity\TypeOfBet;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BetTest extends KernelTestCase
{
    public function testAssertInstanceOfBet(): void
    {
        $bet = new Bet();
        $this->assertInstanceOf(Bet::class, $bet);
        $this->assertClassHasAttribute('id', Bet::class);
        $this->assertClassHasAttribute('listOfOdds', Bet::class);
        $this->assertClassHasAttribute('betLimitTime', Bet::class);
        $this->assertClassHasAttribute('typeOfBet', Bet::class);
        $this->assertClassHasAttribute('betOpened', Bet::class);
        $this->assertClassHasAttribute('betResult', Bet::class);
    }

    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(Bet $bet, ?array $groups): int
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        assert($validator instanceof ValidatorInterface);
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
            [Bet::build('2021-12-12', [
                0 => ['V1' => 1.2],
                1 => ['N' => 2.2],
                2 => ['V2' => 3.2],
            ], 0), ['bet'] , 0],
            [Bet::build('2022-12-12', null, 5), ['limitTime'], 0],
            [Bet::build('2022-12-12', [
                0 => ['V1' => 1.2],
                1 => ['N' => 2.2],
                2 => ['V2' => 3.2],
            ], 3), ['bet'], 0],

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
            ], null), ['bet'] , 1],
            [Bet::build(null, null, 5), ['limitTime'], 1],
            [Bet::build('2022-12-12', null, 3), ['bet'], 1],

        ];
    }

    public function testBetOpened(): void
    {
        $bet = new Bet();
        $bet->openBet();

        $this->assertTrue($bet->isBetOpened());
    }

    public function testBetClosed(): void
    {
        $bet = new Bet();
        $bet->closeBet();

        $this->assertFalse($bet->isBetOpened());
    }

    public function testValidTypeOfBet(): void
    {
        $bet = new Bet();
        $bet->openBet();
        $bet->setBetLimitTime(DateTime::createFromFormat('Y-m-d', "2022-12-12"));
        $bet->setListOfOdds([
            0 => ['V1' => 1.2],
            1 => ['N' => 2.2],
            2 => ['V2' => 3.2],
        ]);
        $typeOfBet = new TypeOfBet();
        $typeOfBet->setBetType('Test');

        $bet->setTypeOfBet($typeOfBet);

        $this->assertInstanceOf(TypeOfBet::class, $bet->getTypeOfBet());
        $this->assertSame(0, $this->getViolationsCount($bet, ['Default']));
    }

    public function testInvalidTypeOfBet(): void
    {
        $bet = new Bet();
        $bet->openBet();
        $bet->setBetLimitTime(DateTime::createFromFormat('Y-m-d', "2022-12-12"));
        $bet->setListOfOdds([
            0 => ['V1' => 1.2],
            1 => ['N' => 2.2],
            2 => ['V2' => 3.2],
        ]);
        $typeOfBet = new TypeOfBet();
        $typeOfBet->setBetType('');

        $bet->setTypeOfBet($typeOfBet);

        $this->assertSame(1, $this->getViolationsCount($bet, ['Default']));
    }
}
