<?php

namespace App\Tests\Unit;

use App\Entity\Wallet;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class WalletTest extends KernelTestCase
{
    public function testAssertInstanceOfWallet()
    {
        $wallet = new Wallet();
        $this->assertInstanceOf(Wallet::class, $wallet);
        $this->assertClassHasAttribute('id', Wallet::class);
        $this->assertClassHasAttribute('balance', Wallet::class);
        $this->assertClassHasAttribute('limitAmountPerWeek', Wallet::class);
        $this->assertClassHasAttribute('realMoney', Wallet::class);
    }


    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(Wallet $wallet, $groups): int
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($wallet, null, $groups);
        //var_dump($violationList);
        return count($violationList);
    }


    /************************$balance*********************************/

    /**
     * @dataProvider validBalanceProvider
     */
    public function testValidBalance($balance, $groups, $expectedViolationsCount): void
    {
        $wallet = new Wallet();
        $wallet->setBalance($balance);
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($wallet, $groups));
    }

    public function validBalanceProvider(): array
    {
        return [
            [2, ['balance'], 0],
            [23, ['balance'], 0],
        ];
    }

    /**
     * @dataProvider invalidBalanceProvider
     */
    public function testInvalidBalance($balance, $groups, $expectedViolationsCount): void
    {
        $wallet = new Wallet();
        $wallet->setBalance($balance);
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($wallet, $groups));
    }

    public function invalidBalanceProvider(): array
    {
        return [
            [-258, ['balance'], 1],
            [-45, ['balance'], 1],
        ];
    }

    /************************$limitAmountPerWeek*********************************/

    /**
     * @dataProvider validLimitAmountPerWeekProvider
     */
    public function testValidLimitAmountPerWeek($limitAmountPerWeek, $groups, $expectedViolationsCount): void
    {
        $wallet = new Wallet();
        $wallet->setLimitAmountPerWeek($limitAmountPerWeek);
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($wallet, $groups));
    }

    public function validLimitAmountPerWeekProvider(): array
    {
        return [
            [2, ['limitAmountPerWeek'], 0],
            [23, ['limitAmountPerWeek'], 0],
        ];
    }

    /**
     * @dataProvider invalidLimitAmountPerWeekProvider
     */
    public function testInvalidLimitAmountPerWeek($limitAmountPerWeek, $groups, $expectedViolationsCount): void
    {
        $wallet = new Wallet();
        $wallet->setLimitAmountPerWeek($limitAmountPerWeek);
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($wallet, $groups));
    }

    public function invalidLimitAmountPerWeekProvider(): array
    {
        return [
            [-10, ['limitAmountPerWeek'], 1],
            [100000, ['limitAmountPerWeek'], 1],
        ];
    }

    /**
     * @dataProvider addMoneySuccessfullyProvider
     */
    public function testAddMoneySuccessfully($amount, $balance)
    {
        $wallet = new Wallet();
        $wallet->setBalance($balance);

        $expectedBalance = $wallet->getBalance() + $amount;

        $transactionStatus = $wallet->hasAddedMoney($amount);

        $this->assertSame($expectedBalance, $wallet->getBalance());
        $this->assertTrue($transactionStatus);
    }

    public function addMoneySuccessfullyProvider(): array
    {
        return [
            [1, 0],
            [100000, 145],
        ];
    }

    /**
     * @dataProvider addMoneyFailProvider
     */
    public function testAddMoneyFail($amount, $balance, $groups)
    {
        $wallet = new Wallet();
        $wallet->setBalance($balance);

        $transactionStatus = $wallet->hasAddedMoney($amount);
        $this->assertSame(1, $this->getViolationsCount($wallet, $groups));
        $this->assertFalse($transactionStatus);
    }

    public function addMoneyFailProvider(): array
    {
        return [
            [-20, 0, ['addMoney']],
            [-100, 145, ['addMoney']],
        ];
    }
}
