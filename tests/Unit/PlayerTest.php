<?php

namespace App\Tests;

use App\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class PlayerTest extends KernelTestCase
{
    public function testAssertInstanceOfPlayer(): void
    {
        $player = new Player();
        $this->assertInstanceOf(Player::class, $player);
        $this->assertClassHasAttribute('id', Player::class);
        $this->assertClassHasAttribute('lastName', Player::class);
        $this->assertClassHasAttribute('firstName', Player::class);
        $this->assertClassHasAttribute('playerStatus', Player::class);
        $this->assertClassHasAttribute('ranking', Player::class);
        $this->assertClassHasAttribute('team', Player::class);
        $this->assertClassHasAttribute('sport', Player::class);
    }

    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(Player $data, ?array $groups): int
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($data, null, $groups);
        return count($violationList);
    }


    /**
     * @dataProvider validPlayerDataProvider
     */
    public function testValidPlayer(Player $player, int $expectedViolationsCount): void
    {
        $player =  new Player();
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($player, null));
    }

    public function validPlayerDataProvider(): array
    {
        return [
            [Player::build('doe', 'Jon', 1), 0],
            [Player::build('McCallan', 'James', 3), 0],

        ];
    }


    /**
     * @dataProvider invalidPlayerDataProvider
     */
    public function testInvalidPlayer(Player $buildData, int $expectedViolationsCount): void
    {
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($buildData, null));
    }

    public function invalidPlayerDataProvider(): array
    {
        return [
            [Player::build('j', null, null), 1],
            [Player::build(null, 'J', null), 1],
            [Player::build(null, null, -1), 1],


        ];
    }

    public function testValidPlayerStatus(): void
    {
        $player = new Player();

        $this->assertSame(0, $player->getPlayerStatus());

        $player->activeStatus();
        $this->assertSame(1, $player->getPlayerStatus());

        $player->replacementStatus();
        $this->assertSame(2, $player->getPlayerStatus());

        $player->injuredStatus();
        $this->assertSame(3, $player->getPlayerStatus());

        $player->inactiveStatus();
        $this->assertSame(4, $player->getPlayerStatus());
    }
}
