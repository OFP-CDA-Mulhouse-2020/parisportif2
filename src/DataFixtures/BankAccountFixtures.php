<?php

namespace App\DataFixtures;

use App\Entity\BankAccount;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BankAccountFixtures extends Fixture
{
    public const BANK_ACCOUNT_USER_2 = 'user2_BankAccount';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $bankAccount = new BankAccount();
        $bankAccount->setIbanCode('FR7630006000011234567890189');
        $bankAccount->setBicCode('BNPAFRPPTAS');
        $manager->persist($bankAccount);

        $manager->flush();

        $this->addReference(self::BANK_ACCOUNT_USER_2, $bankAccount);
    }
}
