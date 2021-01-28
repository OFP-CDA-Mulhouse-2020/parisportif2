<?php

namespace App\DataFixtures;

use App\DataFixtures\Sports\Basket\CharlotteHornets\CharlotteHornetsTeamFixtures;
use App\DataFixtures\Sports\Basket\DetroitPistons\DetroitPistonsTeamFixtures;
use App\DataFixtures\Sports\Football\Lyon\LyonTeamFixtures;
use App\DataFixtures\Sports\Football\Strasbourg\StrasbourgTeamFixtures;
use App\Entity\Sport;
use App\Entity\Team;
use DateTime;
use App\Entity\Event;
use App\DataFixtures\Sports\Basket\SportBasketFixtures;
use App\DataFixtures\Sports\Football\SportFootballFixtures;
use App\Entity\Competition;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EventFixtures extends Fixture implements DependentFixtureInterface
{

    public const EVENT_1 = 'football';
    public const EVENT_2 = 'basket';


    public function setEventData(
        string $name,
        string $location,
        string $eventDateTime,
        string $eventTimeZone,
        Sport $sport,
        Competition $competition
    ): Event {
        $event = new Event();

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
        $sport1 = $this->getReference(SportFootballFixtures::SPORT_FOOTBALL);
        $sport2  = $this->getReference(SportBasketFixtures::SPORT_BASKET);
        $teamFootball1  = $this->getReference(LyonTeamFixtures::TEAM_FOOTBALL_OL_LYON);
        $teamFootball2  = $this->getReference(StrasbourgTeamFixtures::TEAM_FOOTBALL_RCS_ALSACE);
        $teamBasket3  = $this->getReference(DetroitPistonsTeamFixtures::TEAM_BASKET_DETROIT_PISTONS);
        $teamBasket4  = $this->getReference(CharlotteHornetsTeamFixtures::TEAM_BASKET_CHARLOTTE_HORNETS);
        assert($competition1 instanceof Competition);
        assert($competition2 instanceof Competition);
        assert($sport1 instanceof Sport);
        assert($sport2 instanceof Sport);
        assert($teamFootball1 instanceof Team);
        assert($teamFootball2 instanceof Team);
        assert($teamBasket3 instanceof Team);
        assert($teamBasket4 instanceof Team);

        $event1 = $this->setEventData(
            '34e journée',
            'Parc Olympique Lyonnais',
            '2021-04-25 21:00:00',
            'Europe/Paris',
            $sport1,
            $competition1
        );


        $event2 = $this->setEventData(
            'finale',
            'Little Caesars Arena, Detroit',
            '2021-07-30 21:00:00',
            'America/Detroit',
            $sport2,
            $competition2
        );


        $event1->addTeam($teamFootball1);
        $event1->addTeam($teamFootball2);
        $event2->addTeam($teamBasket3);
        $event2->addTeam($teamBasket4);

            $manager->persist($event1);
            $manager->persist($event2);
            $manager->flush();

        $this->addReference(self::EVENT_1, $event1);
        $this->addReference(self::EVENT_2, $event2);
    }

    public function getDependencies(): array
    {
        return [
            SportFootballFixtures::class,
            SportBasketFixtures::class,
            CompetitionFixtures::class,
            LyonTeamFixtures::class,
            StrasbourgTeamFixtures::class,
            DetroitPistonsTeamFixtures::class,
            CharlotteHornetsTeamFixtures::class
        ];
    }
}
