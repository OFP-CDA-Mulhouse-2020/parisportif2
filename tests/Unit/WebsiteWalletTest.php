<?php

namespace App\Tests;

use App\Entity\WebsiteWallet;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

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
        $violationList = $validator->validate($websiteWallet, null, $groups);

        return count($violationList);
    }


    // /**
    //  * @dataProvider generateValidBalance
    //  */

    // public function testValidBalance(float $balance, $groups, $numberOfViolations)
    // {
    //     $websiteWallet = new WebsiteWallet();

    //     $this->assertSame($numberOfViolations, $this->getViolationsCount($websiteWallet, $groups));
    // }


    // public function generateValidBalance(): array
    // {
    //     return [
    //         [01222222222222, ['balance'], 0],
    //         [56565, ['balance'], 0],
    //         [5000, ['balance'], 0],

    //     ];
    // }



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
