<?php

namespace App\DataFixtures;

use App\Entity\Wallet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WalletFixtures extends Fixture
{
    public const WALLET_USER_2 = 'user2_wallet';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $wallet = new Wallet();
        $wallet->initializeWallet(true);
        $wallet->setLimitAmountPerWeek(20);
        $wallet->addMoney(50);
        $manager->persist($wallet);

        $manager->flush();

        $this->addReference(self::WALLET_USER_2, $wallet);
    }
}
