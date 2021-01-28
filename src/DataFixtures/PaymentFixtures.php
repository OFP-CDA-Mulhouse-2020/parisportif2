<?php

namespace App\DataFixtures;

use App\Entity\Payment;
use App\Entity\TypeOfPayment;
use App\Entity\Wallet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PaymentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $wallet = $this->getReference(WalletFixtures::WALLET_USER_2);
        $typeOfPayment1 = $this->getReference(TypeOfPaymentFixtures::TYPE_OF_PAYMENT_1);
        $typeOfPayment2 = $this->getReference(TypeOfPaymentFixtures::TYPE_OF_PAYMENT_2);
        $typeOfPayment3 = $this->getReference(TypeOfPaymentFixtures::TYPE_OF_PAYMENT_3);
        $typeOfPayment4 = $this->getReference(TypeOfPaymentFixtures::TYPE_OF_PAYMENT_4);
        assert($wallet instanceof Wallet);
        assert($typeOfPayment1 instanceof TypeOfPayment);
        assert($typeOfPayment2 instanceof TypeOfPayment);
        assert($typeOfPayment3 instanceof TypeOfPayment);
        assert($typeOfPayment4 instanceof TypeOfPayment);

        $payment = new Payment(50);
        $payment->setPaymentName('Ajout de fonds')
                ->setTypeOfPayment($typeOfPayment1)
                ->setWallet($wallet)
                ->acceptPayment()
        ;

        $manager->persist($payment);

        $payment = new Payment(5);
        $payment->setPaymentName('Ticket de pari n°2525')
            ->setTypeOfPayment($typeOfPayment3)
            ->setWallet($wallet)
            ->acceptPayment()
        ;

        $manager->persist($payment);

        $payment = new Payment(10);
        $payment->setPaymentName('Gain sur ticket de pari n°2525')
            ->setTypeOfPayment($typeOfPayment4)
            ->setWallet($wallet)
            ->acceptPayment()
        ;

        $manager->persist($payment);

        $payment = new Payment(20);
        $payment->setPaymentName('Retrait de fonds')
            ->setTypeOfPayment($typeOfPayment2)
            ->setWallet($wallet)
            ->acceptPayment()
        ;

        $manager->persist($payment);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            WalletFixtures::class,
            TypeOfPaymentFixtures::class,
        ];
    }
}
