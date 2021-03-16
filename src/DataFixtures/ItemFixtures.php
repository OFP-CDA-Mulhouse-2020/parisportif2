<?php

namespace App\DataFixtures;

use App\Entity\Bet;
use App\Entity\Item;
use App\Entity\Payment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ItemFixtures extends Fixture implements DependentFixtureInterface
{
    public const ITEM_1 = 'item_1';
    public const ITEM_2 = 'item_2';
    public const ITEM_3 = 'item_3';
    public const ITEM_4 = 'item_4';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $bet1 = $this->getReference(BetFixtures::BET_1);
        $bet2 = $this->getReference(BetFixtures::BET_2);
        $bet3 = $this->getReference(BetFixtures::BET_3);
        $bet4 = $this->getReference(BetFixtures::BET_4);
        $payment9 = $this->getReference(PaymentFixtures::PAYMENT_9);

        assert($bet1 instanceof Bet);
        assert($bet2 instanceof Bet);
        assert($bet3 instanceof Bet);
        assert($bet4 instanceof Bet);
        assert($payment9 instanceof Payment);


        $item1 = new Item($bet1);
        $item1->isModifiedAmount(5);
        $item1->isModifiedRecordedOdds(2.2);
        $item1->setExpectedBetResult(0);
        $item1->setPayment($payment9);
        $item1->payItem();
        $manager->persist($item1);


        $item2 = new Item($bet2);
        $item2->isModifiedAmount(5);
        $item2->isModifiedRecordedOdds(1.3);
        $item2->setExpectedBetResult(1);
        $item2->setPayment($payment9);
        $item2->payItem();
        $manager->persist($item2);

        $item3 = new Item($bet3);
        $item3->isModifiedAmount(5);
        $item3->isModifiedRecordedOdds(1.1);
        $item3->setExpectedBetResult(2);
        $manager->persist($item3);


        $item4 = new Item($bet4);
        $item4->isModifiedAmount(5);
        $item4->isModifiedRecordedOdds(2.2);
        $item4->setExpectedBetResult(0);
        $manager->persist($item4);

        $manager->flush();

        $this->addReference(self::ITEM_1, $item1);
        $this->addReference(self::ITEM_2, $item2);
        $this->addReference(self::ITEM_3, $item3);
        $this->addReference(self::ITEM_4, $item4);
    }


    public function getDependencies(): array
    {
        return [
            BetFixtures::class,
            PaymentFixtures::class,
        ];
    }
}
