<?php

namespace App\DataFixtures;

use App\Entity\TypeOfPayment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeOfPaymentFixtures extends Fixture
{
    public const TYPE_OF_PAYMENT_1 = 'External Transfer Add Money To Wallet';
    public const TYPE_OF_PAYMENT_2 = 'External Transfer Withdraw Money From Wallet';
    public const TYPE_OF_PAYMENT_3 = 'Internal Transfer Bet Payment';
    public const TYPE_OF_PAYMENT_4 = 'Internal Transfer Bet Earning';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $typeOfPayment1 = new TypeOfPayment();
        $typeOfPayment1->setTypeOfPayment('External Transfer Add Money To Wallet');
        $manager->persist($typeOfPayment1);

        $typeOfPayment2 = new TypeOfPayment();
        $typeOfPayment2->setTypeOfPayment('External Transfer Withdraw Money From Wallet');
        $manager->persist($typeOfPayment2);

        $typeOfPayment3 = new TypeOfPayment();
        $typeOfPayment3->setTypeOfPayment('Internal Transfer Bet Payment');
        $manager->persist($typeOfPayment3);

        $typeOfPayment4 = new TypeOfPayment();
        $typeOfPayment4->setTypeOfPayment('Internal Transfer Bet Earning');
        $manager->persist($typeOfPayment4);

        $this->addReference(self::TYPE_OF_PAYMENT_1, $typeOfPayment1);
        $this->addReference(self::TYPE_OF_PAYMENT_2, $typeOfPayment2);
        $this->addReference(self::TYPE_OF_PAYMENT_3, $typeOfPayment3);
        $this->addReference(self::TYPE_OF_PAYMENT_4, $typeOfPayment4);

        $manager->flush();
    }
}
