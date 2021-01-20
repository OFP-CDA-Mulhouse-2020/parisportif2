<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Event;
use App\DataFixtures\Sports\Basket\SportBasketFixtures;
use App\DataFixtures\Sports\Football\SportFootballFixtures;
use App\Entity\Competition;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use PhpParser\Node\Stmt\ClassConst;

class EventFixtures extends Fixture implements DependentFixtureInterface
{

    public function setEventData(
        string $reference,
        string $name,
        string $location,
        string $eventDateTime,
        string $eventTimeZone,
        object $competition
    ): object {
        $event = new Event();

        $sport = $this->getReference($reference);

        $event->setName($name)
            ->setLocation($location)
            ->setEventDateTime(new Datetime($eventDateTime))
            ->setEventTimeZone($eventTimeZone)
            ->setSport($sport)
            ->setCompetition($competition);

        return $event;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        // array_push($event, 'name', 'location', 'yyyy-mm-dd', 'Europe/Paris');
        $competition1 = $this->getReference(CompetitionFixtures::COMPETITION_LIGUE_1);
        $competition2  = $this->getReference(CompetitionFixtures::COMPETITION_NBA);

        $events = array();

        array_push(
            $events,
            $this->setEventData(
                SportFootballFixtures::SPORT_FOOTBALL,
                '34e journée',
                'Parc Olympique Lyonnais',
                '2021-04-25 21:00:00',
                'Europe/Paris',
                $competition1
            )
        );

        array_push(
            $events,
            $this->setEventData(
                SportBasketFixtures::SPORT_BASKET,
                'finale',
                'Little Caesars Arena, Detroit',
                '2021-07-30 21:00:00',
                'America/Detroit',
                $competition2
            )
        );



        foreach ($events as $event) {
            $manager->persist($event);
            $manager->flush();
        }
    }

    public function getDependencies(): array
    {
        return [
            SportFootballFixtures::class,
            SportBasketFixtures::class,
            CompetitionFixtures::class,
        ];
    }
}
