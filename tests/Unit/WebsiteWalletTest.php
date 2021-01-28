<?php

namespace App\Tests\Unit;

use App\Entity\WebsiteWallet;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WebsiteWalletTest extends KernelTestCase
{
    public function testWebsiteWalletInstance(): void
    {
        $websiteWallet = new WebsiteWallet();
        $this->assertInstanceOf(WebsiteWallet::class, $websiteWallet);
        $this->assertClassHasAttribute('balance', WebsiteWallet::class);
    }

    /******************************** kernel ****************************** */

    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(WebsiteWallet $websiteWallet, ?array $groups): int
    {
        $kernel = $this->getKernel();
        $validator = $kernel->getContainer()->get('validator');
        assert($validator instanceof ValidatorInterface);
        $violationList = $validator->validate($websiteWallet, null, $groups);

        return count($violationList);
    }

    /**
     * @dataProvider generateAddMoney
     */
    public function testAddMoneyToAccount(float $money, ?array $groups, int $numberOfViolations): void
    {
        $websiteWallet = new WebsiteWallet();
        $websiteWallet->initializeWallet();

        $newBalance = $websiteWallet->addToBalance($money);

        $this->assertSame($websiteWallet->getBalance() * 100, $newBalance);
        $this->assertSame($numberOfViolations, $this->getViolationsCount($websiteWallet, $groups));
    }

    public function generateAddMoney(): array
    {
        return [
            [50, null, 0],
            [-100001, null, 0],
        ];
    }

    /**
     * @dataProvider generateRemoveMoney
     */
    public function testRemoveMoneyToWallet(float $money, ?array $groups, int $numberOfViolations): void
    {
        $websiteWallet = new WebsiteWallet();
        $websiteWallet->initializeWallet();

        $newBalance = $websiteWallet->removeFromBalance($money);

        $this->assertSame($websiteWallet->getBalance() * 100, $newBalance);
        $this->assertSame($numberOfViolations, $this->getViolationsCount($websiteWallet, $groups));
    }

    public function generateRemoveMoney(): array
    {
        return [
            [50, null, 0],
            [100000, null, 0],
            [10000000, null, 0],
        ];
    }
}
