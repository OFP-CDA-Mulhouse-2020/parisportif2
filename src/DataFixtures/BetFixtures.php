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
        $typeOfBet3 = $this->getReference(TypeOfBetFixtures::TYPE_OF_BET_3);
        $typeOfBet4 = $this->getReference(TypeOfBetFixtures::TYPE_OF_BET_4);
        $typeOfBet5 = $this->getReference(TypeOfBetFixtures::TYPE_OF_BET_5);
        $typeOfBet6 = $this->getReference(TypeOfBetFixtures::TYPE_OF_BET_6);

        $event1 = $this->getReference(EventFixtures::EVENT_1);
        $event2 = $this->getReference(EventFixtures::EVENT_2);
        $event3 = $this->getReference(EventFixtures::EVENT_3);
        $event4 = $this->getReference(EventFixtures::EVENT_4);
        $event5 = $this->getReference(EventFixtures::EVENT_5);
        $event6 = $this->getReference(EventFixtures::EVENT_6);
        $event7 = $this->getReference(EventFixtures::EVENT_7);
        $event8 = $this->getReference(EventFixtures::EVENT_8);

        assert($typeOfBet1 instanceof TypeOfBet);
        assert($typeOfBet2 instanceof TypeOfBet);
        assert($typeOfBet3 instanceof TypeOfBet);
        assert($typeOfBet4 instanceof TypeOfBet);
        assert($typeOfBet5 instanceof TypeOfBet);
        assert($typeOfBet6 instanceof TypeOfBet);

        assert($event1 instanceof Event);
        assert($event2 instanceof Event);
        assert($event3 instanceof Event);
        assert($event4 instanceof Event);
        assert($event5 instanceof Event);
        assert($event6 instanceof Event);
        assert($event7 instanceof Event);
        assert($event8 instanceof Event);

        // bet football
        $bet1 = new Bet();
        $bet1->setBetLimitTime((new DateTime())->add(new DateInterval('P2D')));
        $bet1->setListOfOdds([
            ['Team1', 2.2],
            ['Nul', 1.5],
            ['Team2', 1.1],
        ]);
        $bet1->setTypeOfBet($typeOfBet1);
        $bet1->setEvent($event1);
        $bet1->openBet();
        $manager->persist($bet1);

        $bet9 = new Bet();
        $bet9->setBetLimitTime((new DateTime())->add(new DateInterval('P2D')));
        $bet9->setListOfOdds([
            ['1-0', 2.2],
            ['0-0', 2.2],
            ['0-1', 2.2],

            ['2-0', 2.2],
            ['1-1', 2.2],
            ['0-2', 2.2],

            ['2-1', 2.2],
            ['2-2', 2.2],
            ['1-2', 2.2],

            ['3-0', 2.2],
            ['3-3', 2.2],
            ['0-3', 2.2],

            ['3-1', 2.2],
            ['4-4', 2.2],
            ['1-3', 2.2],

            ['3-2', 2.2],
            ['5-5', 2.2],
            ['2-3', 2.2],
        ]);
        $bet9->setTypeOfBet($typeOfBet5);
        $bet9->setEvent($event1);
        $bet9->openBet();
        $manager->persist($bet9);


        $bet10 = new Bet();
        $bet10->setBetLimitTime((new DateTime())->add(new DateInterval('P2D')));
        $bet10->setListOfOdds([
            ['Plus de 0.5', 2.2],
            ['Moins de 0.5', 1.5],

            ['Plus de 1.5', 2.2],
            ['Moins de 1.5', 1.5],

            ['Plus de 2.5', 2.2],
            ['Moins de 2.5', 1.5],

            ['Plus de 3.5', 2.2],
            ['Moins de 3.5', 1.5],

            ['Plus de 4.5', 2.2],
            ['Moins de 4.5', 1.5],

            ['Plus de 5.5', 2.2],
            ['Moins de 5.5', 1.5],
            ]);

        $bet10->setTypeOfBet($typeOfBet3);
        $bet10->setEvent($event1);
        $bet10->openBet();
        $manager->persist($bet10);


        $bet11 = new Bet();
        $bet11->setBetLimitTime((new DateTime())->add(new DateInterval('P2D')));
        $bet11->setListOfOdds([
            ['Lyon/Lyon', 2.2],
            ['Lyon/Match Nul', 1.5],
            ['Lyon/Strabourg', 1.5],

            ['Match Nul/Lyon', 2.2],
            ['Match Nul/Match Nul', 1.5],
            ['Match Nul/Strabourg', 1.5],

            ['Strabourg/Lyon', 2.2],
            ['Strabourg/Match Nul', 1.5],
            ['Strabourg/Strabourg', 1.5],
        ]);

        $bet11->setTypeOfBet($typeOfBet6);
        $bet11->setEvent($event1);
        $bet11->openBet();
        $manager->persist($bet11);

        // bet basket
        $bet2 = new Bet();
        $bet2->setBetLimitTime((new DateTime())->add(new DateInterval('P2D')));
        $bet2->setListOfOdds([
            ['Team1', 2.2],
            ['Team2', 1.3],
            ]);
        $bet2->setTypeOfBet($typeOfBet2);
        $bet2->setEvent($event2);
        $bet2->openBet();
        $manager->persist($bet2);

        // bet football
        $bet3 = new Bet();
        $bet3->setBetLimitTime((new DateTime())->add(new DateInterval('P2D')));
        $bet3->setListOfOdds([
            ['Team1', 2.2],
            ['Nul', 1.5],
            ['Team2', 1.1],
        ]);
        $bet3->setTypeOfBet($typeOfBet1);
        $bet3->setEvent($event3);
        $bet3->openBet();
        $manager->persist($bet3);

        // bet basket
        $bet4 = new Bet();
        $bet4->setBetLimitTime((new DateTime())->add(new DateInterval('P2D')));
        $bet4->setListOfOdds([
            ['Team1', 2.2],
            ['Team2', 1.3],
        ]);
        $bet4->setTypeOfBet($typeOfBet2);
        $bet4->setEvent($event4);
        $bet4->openBet();
        $manager->persist($bet4);

        // bet football
        $bet5 = new Bet();
        $bet5->setBetLimitTime((new DateTime())->add(new DateInterval('P2D')));
        $bet5->setListOfOdds([
            ['Team1', 2.2],
            ['Nul', 1.5],
            ['Team2', 1.1],
        ]);
        $bet5->setTypeOfBet($typeOfBet1);
        $bet5->setEvent($event5);
        $bet5->openBet();
        $manager->persist($bet5);

        // bet basket
        $bet6 = new Bet();
        $bet6->setBetLimitTime((new DateTime())->add(new DateInterval('P2D')));
        $bet6->setListOfOdds([
            ['Team1', 2.2],
            ['Team2', 1.3],
        ]);
        $bet6->setTypeOfBet($typeOfBet2);
        $bet6->setEvent($event6);
        $bet6->openBet();
        $manager->persist($bet6);

        // bet football
        $bet7 = new Bet();
        $bet7->setBetLimitTime((new DateTime())->add(new DateInterval('P2D')));
        $bet7->setListOfOdds([
            ['Team1', 2.2],
            ['Nul', 1.5],
            ['Team2', 1.1],
        ]);
        $bet7->setTypeOfBet($typeOfBet1);
        $bet7->setEvent($event7);
        $bet7->openBet();
        $manager->persist($bet7);

        // bet basket
        $bet8 = new Bet();
        $bet8->setBetLimitTime((new DateTime())->add(new DateInterval('P2D')));
        $bet8->setListOfOdds([
            ['Team1', 2.2],
            ['Team2', 1.3],
        ]);
        $bet8->setTypeOfBet($typeOfBet2);
        $bet8->setEvent($event8);
        $bet8->openBet();
        $manager->persist($bet8);

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
