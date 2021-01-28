<?php

namespace App\DataFixtures;

use App\Entity\Bet;
use App\Entity\Event;
use App\Entity\TypeOfBet;
use DateInterval;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BetFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $typeOfBet1 = $this->getReference(TypeOfBetFixtures::TYPE_OF_BET_1);
        $typeOfBet2 = $this->getReference(TypeOfBetFixtures::TYPE_OF_BET_2);
        $event1 = $this->getReference(EventFixtures::EVENT_1);
        $event2 = $this->getReference(EventFixtures::EVENT_2);
        assert($typeOfBet1 instanceof TypeOfBet);
        assert($typeOfBet2 instanceof TypeOfBet);
        assert($event1 instanceof Event);
        assert($event2 instanceof Event);


        $bet1 = new Bet();
        $bet1->setBetLimitTime((new DateTime())->add(new DateInterval('P2D')));
        $bet1->setListOfOdds([2.2, 1.5, 1.1]);
        $bet1->setTypeOfBet($typeOfBet1);
        $bet1->setEvent($event1);
        $bet1->openBet();
        $manager->persist($bet1);

        // bet basket
        $bet2 = new Bet();
        $bet2->setBetLimitTime((new DateTime())->add(new DateInterval('P2D')));
        $bet2->setListOfOdds([2, 1]);
        $bet2->setTypeOfBet($typeOfBet2);
        $bet2->setEvent($event2);
        $bet2->openBet();

        $manager->persist($bet2);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TypeOfBetFixtures::class,
            EventFixtures::class,

        ];
    }
}
