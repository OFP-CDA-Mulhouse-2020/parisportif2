<?php

namespace App\DataFixtures;

use App\Entity\BankAccount;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BankAccountFixtures extends Fixture
{
    public const BANK_ACCOUNT_1 = 'bankAccount_1';
    public const BANK_ACCOUNT_2 = 'bankAccount_2';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $bankAccount1 = new BankAccount();
        $bankAccount1->setIbanCode('FR7630006000011234567890189');
        $bankAccount1->setBicCode('BNPAFRPPTAS');
        $manager->persist($bankAccount1);

        $bankAccount2 = new BankAccount();
        $bankAccount2->setIbanCode('FR7630006000011234567890189');
        $bankAccount2->setBicCode('BNPAFRPPTAS');
        $manager->persist($bankAccount2);

        $manager->flush();

        $this->addReference(self::BANK_ACCOUNT_1, $bankAccount1);
        $this->addReference(self::BANK_ACCOUNT_2, $bankAccount2);
    }
}
