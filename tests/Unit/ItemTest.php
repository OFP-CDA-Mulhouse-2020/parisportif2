<?php

namespace App\Tests\Unit;

use App\Entity\Bet;
use App\Entity\Item;
use App\Entity\Payment;
use App\Entity\Wallet;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class ItemTest extends KernelTestCase
{
    public function testAssertInstanceOfItem(): void
    {
        $item = new Item(new Bet());
        $this->assertInstanceOf(Item::class, $item);
        $this->assertClassHasAttribute('id', Item::class);
        $this->assertClassHasAttribute('bet', Item::class);
        $this->assertClassHasAttribute('expectedBetResult', Item::class);
        $this->assertClassHasAttribute('recordedOdds', Item::class);
        $this->assertClassHasAttribute('amount', Item::class);
        $this->assertClassHasAttribute('itemStatusId', Item::class);
        $this->assertClassHasAttribute('cart', Item::class);
        $this->assertClassHasAttribute('payment', Item::class);
    }

    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(Item $item, ?array $groups): int
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($item, null, $groups);
        //var_dump($violationList);
        return count($violationList);
    }

    /**
     * @dataProvider validItemProvider
     */
    public function testValidItem(
        int $expectedBetResult,
        float $recordedOdds,
        float $amount,
        int $expectedViolationsCount
    ): void {
        $item = new Item(new Bet());
        $item->isModifiedAmount($amount);
        $item->setExpectedBetResult($expectedBetResult);
        $item->setRecordedOdds($recordedOdds);

        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($item, null));

        $this->assertSame($expectedBetResult, $item->getExpectedBetResult());
        $this->assertSame($recordedOdds, $item->getRecordedOdds());
        $this->assertSame($amount, $item->getAmount());
        $this->assertSame(0, $item->getItemStatusId());
    }

    public function validItemProvider(): array
    {
        return [
            [2, 1.23, 5.5, 0],
            [23, 2.52, 15, 0],
        ];
    }

    /**
     * @dataProvider invalidItemProvider
     */
    public function testInvalidItem(
        int $expectedBetResult,
        float $recordedOdds,
        float $amount,
        int $expectedViolationsCount
    ): void {
        $item = new Item(new Bet());
        $item->isModifiedAmount($amount);
        $item->setExpectedBetResult($expectedBetResult);
        $item->setRecordedOdds($recordedOdds);
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($item, null));
    }

    public function invalidItemProvider(): array
    {
        return [
            [-2, 123, 5, 1],
            [2, 0.99, 5.2, 1],
            [23, -150, -5, 2],
        ];
    }

    public function testIsModifiedAmount(): void
    {
        $item = new Item(new Bet());
        $status = $item->isModifiedAmount(50);
        $this->assertSame(50.00, $item->getAmount());
        $this->assertTrue($status);
    }

    public function testIsNotModifiedAmount(): void
    {
        $item = new Item(new Bet());
        $item->isModifiedAmount(20);
        $item->payItem();
        $status = $item->isModifiedAmount(50);
        $this->assertSame(20.00, $item->getAmount());
        $this->assertFalse($status);
    }

    public function testValidItemStatus(): void
    {
        $item = new Item(new Bet());
        $this->assertSame(0, $this->getViolationsCount($item, ['ItemStatus']));
        $this->assertSame(0, $item->getItemStatusId());

        $item->payItem();
        $this->assertSame(1, $item->getItemStatusId());

        $item->winItem();
        $this->assertSame(2, $item->getItemStatusId());

        $item->looseItem();
        $this->assertSame(3, $item->getItemStatusId());

        $item->refundItem();
        $this->assertSame(4, $item->getItemStatusId());

        $item->closeItem();
        $this->assertSame(5, $item->getItemStatusId());
    }

    public function testValidCalculateProfitsForItemNotPayed(): void
    {
        $item = new Item(new Bet());
        $item->isModifiedAmount(10);
        $item->setRecordedOdds(2.22);

        $item->calculateProfits();
        $this->assertSame(null, $item->calculateProfits());
    }

    public function testValidCalculateProfitsForItemPayed(): void
    {
        $item = new Item(new Bet());
        $item->isModifiedAmount(10);
        $item->setRecordedOdds(2.22);

        $item->payItem();

        $item->calculateProfits();
        $this->assertSame(null, $item->calculateProfits());
    }

    public function testValidCalculateProfitsForWinItem(): void
    {
        $item = new Item(new Bet());
        $item->isModifiedAmount(10);
        $item->setRecordedOdds(2.22);

        $item->winItem();

        $item->calculateProfits();
        $this->assertSame(22.2, $item->calculateProfits());
    }

    public function testValidCalculateProfitsForLooseItem(): void
    {
        $item = new Item(new Bet());
        $item->isModifiedAmount(10);
        $item->setRecordedOdds(2.22);

        $item->looseItem();

        $item->calculateProfits();
        $this->assertSame(null, $item->calculateProfits());
    }

    public function testValidCalculateProfitsForRefundItem(): void
    {
        $item = new Item(new Bet());
        $item->isModifiedAmount(10);
        $item->setRecordedOdds(2.22);

        $item->refundItem();

        $item->calculateProfits();
        $this->assertSame(10.00, $item->calculateProfits());
    }

    public function testValidCalculateProfitsForCloseItem(): void
    {
        $item = new Item(new Bet());
        $item->isModifiedAmount(10);
        $item->setRecordedOdds(2.22);

        $item->closeItem();

        $item->calculateProfits();
        $this->assertSame(null, $item->calculateProfits());
    }


    public function testValidGeneratePayment(): void
    {
        $item = new Item(new Bet());
        $item->isModifiedAmount(10);
        $payment = new Payment(10);
        $wallet = new Wallet();
        $payment->setWallet($wallet);
        $item->setPayment($payment);
        $item->setRecordedOdds(2.22);

        $item->winItem();

        $result = $item->generatePayment();

        $this->assertInstanceOf(Payment::class, $result);
        $this->assertSame(22.2, $result->getSum());
        $this->assertSame($item, $result->getItems()[0]);
    }
}
