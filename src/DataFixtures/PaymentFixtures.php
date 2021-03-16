<?php

namespace App\DataFixtures;

use App\Entity\Cart;
use App\Entity\Payment;
use App\Entity\TypeOfPayment;
use App\Entity\Wallet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PaymentFixtures extends Fixture implements DependentFixtureInterface
{
    public const PAYMENT_9 = 'payment_9';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $wallet1 = $this->getReference(WalletFixtures::WALLET_1);
        $wallet2 = $this->getReference(WalletFixtures::WALLET_2);
        $typeOfPayment1 = $this->getReference(TypeOfPaymentFixtures::TYPE_OF_PAYMENT_1);
        $typeOfPayment2 = $this->getReference(TypeOfPaymentFixtures::TYPE_OF_PAYMENT_2);
        $typeOfPayment3 = $this->getReference(TypeOfPaymentFixtures::TYPE_OF_PAYMENT_3);
        $typeOfPayment4 = $this->getReference(TypeOfPaymentFixtures::TYPE_OF_PAYMENT_4);


        assert($wallet1 instanceof Wallet);
        assert($wallet2 instanceof Wallet);
        assert($typeOfPayment1 instanceof TypeOfPayment);
        assert($typeOfPayment2 instanceof TypeOfPayment);
        assert($typeOfPayment3 instanceof TypeOfPayment);
        assert($typeOfPayment4 instanceof TypeOfPayment);



        $payment1 = new Payment(50);
        $payment1->setPaymentName('Ajout de fonds')
                ->setTypeOfPayment($typeOfPayment1)
                ->setWallet($wallet1)
                ->acceptPayment()
        ;
        $manager->persist($payment1);

        $payment2 = new Payment(5);
        $payment2->setPaymentName('Ticket de pari n°2525')
            ->setTypeOfPayment($typeOfPayment3)
            ->setWallet($wallet1)
            ->acceptPayment()
        ;
        $manager->persist($payment2);

        $payment3 = new Payment(10);
        $payment3->setPaymentName('Gain sur ticket de pari n°2525')
            ->setTypeOfPayment($typeOfPayment4)
            ->setWallet($wallet1)
            ->acceptPayment()
        ;
        $manager->persist($payment3);

        $payment4 = new Payment(20);
        $payment4->setPaymentName('Retrait de fonds')
            ->setTypeOfPayment($typeOfPayment2)
            ->setWallet($wallet1)
            ->acceptPayment()
        ;

        $manager->persist($payment4);


        $payment5 = new Payment(50);
        $payment5->setPaymentName('Ajout de fonds')
            ->setTypeOfPayment($typeOfPayment1)
            ->setWallet($wallet2)
            ->acceptPayment()
        ;
        $manager->persist($payment5);

        $payment6 = new Payment(5);
        $payment6->setPaymentName('Ticket de pari n°2525')
            ->setTypeOfPayment($typeOfPayment3)
            ->setWallet($wallet2)
            ->acceptPayment()
        ;
        $manager->persist($payment6);

        $payment7 = new Payment(10);
        $payment7->setPaymentName('Gain sur ticket de pari n°2525')
            ->setTypeOfPayment($typeOfPayment4)
            ->setWallet($wallet2)
            ->acceptPayment()
        ;
        $manager->persist($payment7);

        $payment8 = new Payment(20);
        $payment8->setPaymentName('Retrait de fonds')
            ->setTypeOfPayment($typeOfPayment2)
            ->setWallet($wallet2)
            ->acceptPayment()
        ;
        $manager->persist($payment8);


        $payment9 = new Payment(10);
        $payment9->setPaymentName('Ticket de pari n°4545')
            ->setTypeOfPayment($typeOfPayment3)
            ->setWallet($wallet1)
            ->acceptPayment()
        ;
        $manager->persist($payment9);

        $manager->flush();

        $this->addReference(self::PAYMENT_9, $payment9);
    }

    public function getDependencies(): array
    {
        return [
            WalletFixtures::class,
            TypeOfPaymentFixtures::class,
        ];
    }
}
