<?php

namespace App\DataFixtures;

use App\DataFixtures\Sports\Basketball\CharlotteHornets\CharlotteHornetsTeamFixtures;
use App\DataFixtures\Sports\Basketball\DetroitPistons\DetroitPistonsTeamFixtures;
use App\DataFixtures\Sports\Football\Lyon\LyonTeamFixtures;
use App\DataFixtures\Sports\Football\Strasbourg\StrasbourgTeamFixtures;
use App\Entity\Sport;
use App\Entity\Team;
use DateInterval;
use DateTime;
use App\Entity\Event;
use App\DataFixtures\Sports\Basketball\SportBasketballFixtures;
use App\DataFixtures\Sports\Football\SportFootballFixtures;
use App\Entity\Competition;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EventFixtures extends Fixture implements DependentFixtureInterface
{

    public const EVENT_1 = 'football1';
    public const EVENT_2 = 'basketball2';
    public const EVENT_3 = 'football3';
    public const EVENT_4 = 'basketball4';
    public const EVENT_5 = 'football5';
    public const EVENT_6 = 'basketball6';
    public const EVENT_7 = 'football7';
    public const EVENT_8 = 'basketball8';


    public function setEventData(
        string $name,
        string $location,
        \DateTimeInterface $eventDateTime,
        string $eventTimeZone,
        Sport $sport,
        Competition $competition
    ): Event {
        $event = new Event();

        $event->setName($name)
            ->setLocation($location)
            ->setEventDateTime($eventDateTime)
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
        $sport2  = $this->getReference(SportBasketballFixtures::SPORT_BASKETBALL);
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
            '5e journée',
            'Parc Olympique Lyonnais',
            new DateTime(),
            'Europe/Paris',
            $sport1,
            $competition1
        );
        $event2 = $this->setEventData(
            '12e journée',
            'Little Caesars Arena, Detroit',
            new DateTime(),
            'America/Detroit',
            $sport2,
            $competition2
        );
        $event3 = $this->setEventData(
            '5e journée',
            'Parc des Princes',
            (new DateTime())->add(new DateInterval('P2D')),
            'Europe/Paris',
            $sport1,
            $competition1
        );
        $event4 = $this->setEventData(
            '12e journée',
            'Staples Center, Los Angeles',
            (new DateTime())->add(new DateInterval('P2D')),
            'America/Detroit',
            $sport2,
            $competition2
        );
        $event5 = $this->setEventData(
            '26e journée',
            'Parc Olympique Lyonnais',
            (new DateTime())->add(new DateInterval('P2D')),
            'Europe/Paris',
            $sport1,
            $competition1
        );


        $event6 = $this->setEventData(
            '23e journée',
            'Little Caesars Arena, Detroit',
            (new DateTime())->add(new DateInterval('P2D')),
            'America/Detroit',
            $sport2,
            $competition2
        );
        $event7 = $this->setEventData(
            '26e journée',
            'Parc des princes',
            (new DateTime())->add(new DateInterval('P2D')),
            'Europe/Paris',
            $sport1,
            $competition1
        );


        $event8 = $this->setEventData(
            '23e journée',
            'Staples Center, Los Angeles',
            (new DateTime())->add(new DateInterval('P2D')),
            'America/Detroit',
            $sport2,
            $competition2
        );

        // foot
        $event1->addTeam($teamFootball1);
        $event1->addTeam($teamFootball2);
        // basket
        $event2->addTeam($teamBasket3);
        $event2->addTeam($teamBasket4);
        // foot
        $event3->addTeam($teamFootball1);
        $event3->addTeam($teamFootball2);
        // basket
        $event4->addTeam($teamBasket3);
        $event4->addTeam($teamBasket4);
        // foot
        $event5->addTeam($teamFootball1);
        $event5->addTeam($teamFootball2);
        // basket
        $event6->addTeam($teamBasket3);
        $event6->addTeam($teamBasket4);
        // foot
        $event7->addTeam($teamFootball1);
        $event7->addTeam($teamFootball2);
        // basket
        $event8->addTeam($teamBasket3);
        $event8->addTeam($teamBasket4);
        //manager persist
        $manager->persist($event1);
        $manager->persist($event2);
        $manager->persist($event3);
        $manager->persist($event4);
        $manager->persist($event5);
        $manager->persist($event6);
        $manager->persist($event7);
        $manager->persist($event8);
        $manager->flush();

        $this->addReference(self::EVENT_1, $event1);
        $this->addReference(self::EVENT_2, $event2);
        $this->addReference(self::EVENT_3, $event3);
        $this->addReference(self::EVENT_4, $event4);
        $this->addReference(self::EVENT_5, $event5);
        $this->addReference(self::EVENT_6, $event6);
        $this->addReference(self::EVENT_7, $event7);
        $this->addReference(self::EVENT_8, $event8);
    }

    public function getDependencies(): array
    {
        return [
            SportFootballFixtures::class,
            SportBasketballFixtures::class,
            CompetitionFixtures::class,
            LyonTeamFixtures::class,
            StrasbourgTeamFixtures::class,
            DetroitPistonsTeamFixtures::class,
            CharlotteHornetsTeamFixtures::class
        ];
    }
}
