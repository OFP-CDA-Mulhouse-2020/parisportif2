<?php

namespace App\Tests\Unit;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class EventTest extends KernelTestCase
{
    public function testAssertInstanceOfEvent(): void
    {
        $event = new Event();
        $this->assertInstanceOf(Event::class, $event);
        $this->assertClassHasAttribute('id', Event::class);
        $this->assertClassHasAttribute('name', Event::class);
        $this->assertClassHasAttribute('location', Event::class);
        $this->assertClassHasAttribute('eventDateTime', Event::class);
        $this->assertClassHasAttribute('eventTimeZone', Event::class);
    }

    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(Event $competition, $groups): int
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($competition, null, $groups);
        //var_dump($violationList);
        return count($violationList);
    }

    /**
     * @dataProvider validEventProvider
     */
    public function testValidEvent(Event $event, int $expectedViolationsCount): void
    {
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($event, null));
    }

    public function validEventProvider(): array
    {
        return [
            [Event::build('Psg - Lyon', 'Parc des Princes, Paris FR', '2021-06-30 15:45:00', 'Pacific/Nauru'), 0],
            [Event::build('JO : ski de fond Hommes', 'Stade de la meinau, Canada CA', '2021-06-30', 'Europe/Paris'), 0],
        ];
    }

    /**
     * @dataProvider invalidEventProvider
     */
    public function testInvalidEvent(Event $event, int $expectedViolationsCount): void
    {
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($event, null));
    }

    public function invalidEventProvider(): array
    {
        return [
            [Event::build('L', 'Parc des Princes, Paris FR', '2021-06-30', 'Europe/Paris'), 1],
            [Event::build('Jeux Olympiques de Toronto 2020', '', '2021-06-30', 'Europe/Paris'), 1],
            [Event::build('Jeux Olympiques de Toronto 2020', '2021-05-30', '', ''), 2],
            [Event::build('Jeux Olympiques de Toronto 2020', '2021-05-30', '', null), 2],

        ];
    }
}
