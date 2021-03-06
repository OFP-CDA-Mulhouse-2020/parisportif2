<?php

namespace App\DataFixtures;

use App\Entity\Wallet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WalletFixtures extends Fixture
{
    public const WALLET_1 = 'wallet_1';
    public const WALLET_2 = 'wallet_2';
    public const WALLET_3 = 'wallet_3';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $wallet1 = new Wallet();
        $wallet1->initializeWallet(true);
        $wallet1->setLimitAmountPerWeek(20);
        $wallet1->addMoney(50);
        $manager->persist($wallet1);

        $wallet2 = new Wallet();
        $wallet2->initializeWallet(true);
        $wallet2->setLimitAmountPerWeek(20);
        $wallet2->addMoney(50);
        $manager->persist($wallet2);

        $wallet3 = new Wallet();
        $wallet3->initializeWallet(true);
        $wallet3->setLimitAmountPerWeek(20);
        $wallet3->addMoney(50);
        $manager->persist($wallet3);

        $manager->flush();

        $this->addReference(self::WALLET_1, $wallet1);
        $this->addReference(self::WALLET_2, $wallet2);
        $this->addReference(self::WALLET_3, $wallet3);
    }
}
