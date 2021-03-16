<?php

namespace App\Tests\Unit;

use App\Entity\Wallet;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WalletTest extends KernelTestCase
{
    public function testAssertInstanceOfWallet(): void
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

    public function getViolationsCount(Wallet $wallet, ?array $groups): int
    {
        $kernel = $this->getKernel();
        $validator = $kernel->getContainer()->get('validator');
        assert($validator instanceof ValidatorInterface);
        $violationList = $validator->validate($wallet, null, $groups);
        //var_dump($violationList);
        return count($violationList);
    }


    /************************initializeWallet*********************************/


    public function testInitializeWalletWithRealMoney(): void
    {
        $wallet = new Wallet();
        $wallet->initializeWallet(true);
        $this->assertTrue($wallet->isRealMoney());
        $this->assertSame(0.0, $wallet->getBalance());
        $this->assertSame(0, $this->getViolationsCount($wallet, ['wallet']));
    }


    public function testInitializeWalletWithFakeMoney(): void
    {
        $wallet = new Wallet();
        $wallet->initializeWallet(false);
        $this->assertFalse($wallet->isRealMoney());
        $this->assertSame(100.0, $wallet->getBalance());
        $this->assertSame(0, $this->getViolationsCount($wallet, ['wallet']));
    }

    public function testInvalidInitializeWallet(): void
    {
        $wallet = new Wallet();
        $this->assertSame(3, $this->getViolationsCount($wallet, ['wallet']));
    }

    /************************$limitAmountPerWeek*********************************/

    /**
     * @dataProvider validLimitAmountPerWeekProvider
     */
    public function testValidLimitAmountPerWeek(
        int $limitAmountPerWeek,
        ?array $groups,
        int $expectedViolationsCount
    ): void {
        $wallet = new Wallet();
        $wallet->setLimitAmountPerWeek($limitAmountPerWeek);
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($wallet, $groups));
        $this->assertSame($limitAmountPerWeek, $wallet->getLimitAmountPerWeek());
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
    public function testInvalidLimitAmountPerWeek(
        int $limitAmountPerWeek,
        ?array $groups,
        int $expectedViolationsCount
    ): void {
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

    /************************addMoney*********************************/

    /**
     * @dataProvider addMoneySuccessfullyProvider
     */
    public function testAddMoneySuccessfully(int $amount, bool $realMoney): void
    {
        $wallet = new Wallet();
        $wallet->initializeWallet($realMoney);

        $expectedBalance = $wallet->getBalance() + $amount;

        $transactionStatus = $wallet->addMoney($amount);

        $this->assertSame($expectedBalance, $wallet->getBalance());
        $this->assertTrue($transactionStatus);
    }

    public function addMoneySuccessfullyProvider(): array
    {
        return [
            [1, false],
            [100000, true],
        ];
    }

    /**
     * @dataProvider addMoneyFailProvider
     */
    public function testAddMoneyFail(int $amount, bool $realMoney): void
    {
        $wallet = new Wallet();
        $wallet->initializeWallet($realMoney);
        $balance = $wallet->getBalance();

        $transactionStatus = $wallet->addMoney($amount);
        $this->assertSame($balance, $wallet->getBalance());

        $this->assertFalse($transactionStatus);
    }

    public function addMoneyFailProvider(): array
    {
        return [
            [-20, true],
            [-100, false],
        ];
    }

    /************************withdrawMoney*********************************/

    /**
     * @dataProvider withdrawMoneySuccessfullyProvider
     */
    public function testWithdrawMoneySuccessfully(int $withdrawAmount, bool $realMoney): void
    {
        $wallet = new Wallet();
        $wallet->initializeWallet($realMoney);
        $wallet->addMoney(100);

        $expectedBalance = $wallet->getBalance() - $withdrawAmount;
        $transactionStatus = $wallet->withdrawMoney($withdrawAmount);

        $this->assertSame($expectedBalance, $wallet->getBalance());
        $this->assertTrue($transactionStatus);
    }

    public function withdrawMoneySuccessfullyProvider(): array
    {
        return [
            [100, true],
            [200, false],
        ];
    }

    /**
     * @dataProvider withdrawMoneyFailProvider
     */
    public function testWithdrawMoneyFail(int $amount, bool $realMoney): void
    {
        $wallet = new Wallet();
        $wallet->initializeWallet($realMoney);
        $wallet->addMoney(100);

        $balance = $wallet->getBalance();

        $transactionStatus = $wallet->withdrawMoney($amount);
        $this->assertSame($balance, $wallet->getBalance());

        $this->assertFalse($transactionStatus);
    }

    public function withdrawMoneyFailProvider(): array
    {
        return [
            [-10, true],
            [120, true],
            [-10, false],
            [201, false],
        ];
    }

    /************************betPayment*********************************/
    /**
     * @dataProvider validBetPaymentProvider
     */
    public function testValidBetPayment(int $amount, int $amountBetPaymentLastWeek): void
    {
        $wallet = new Wallet();
        $wallet->initializeWallet(true);
        $wallet->addMoney(100);

        $expectedBalance = $wallet->getBalance() - $amount;
        $transactionStatus = $wallet->betPayment($amount, $amountBetPaymentLastWeek);

        $this->assertSame($expectedBalance, $wallet->getBalance());
        $this->assertSame(2, $transactionStatus);
    }

    public function validBetPaymentProvider(): array
    {
        return [
            [50, 50],
            [10, 90],
        ];
    }

    /**
     * @dataProvider invalidBetPaymentProvider
     */
    public function testInvalidBetPayment(int $amount, int $amountBetPaymentLastWeek): void
    {
        $wallet = new Wallet();
        $wallet->initializeWallet(true);
        $wallet->addMoney(100);

        $expectedBalance = $wallet->getBalance();
        $transactionStatus = $wallet->betPayment($amount, $amountBetPaymentLastWeek);

        $this->assertSame($expectedBalance, $wallet->getBalance());
        $this->assertSame(0, $transactionStatus);
    }

    public function invalidBetPaymentProvider(): array
    {
        return [
            [60, 50],
            [11, 90],
            [55, 50],
        ];
    }
}
