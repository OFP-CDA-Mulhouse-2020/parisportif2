<?php

namespace App\Tests;

use App\Entity\PlayerStatus;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class PlayerStatusTest extends KernelTestCase
{
    public function testAssertInstanceOfPlayerStatus(): void
    {
        $playerStatus = new PlayerStatus();
        $this->assertInstanceOf(PlayerStatus::class, $playerStatus);
        $this->assertClassHasAttribute('id', PlayerStatus::class);
        $this->assertClassHasAttribute('playerStatus', PlayerStatus::class);
    }

    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(PlayerStatus $playerStatus, $groups): int
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($playerStatus, null, $groups);
        //var_dump($violationList);
        return count($violationList);
    }

    /**
     * @dataProvider validPlayerStatusProvider
     */
    public function testValidPlayerStatus(string $status, int $expectedViolationsCount): void
    {
        $playerStatus = new PlayerStatus();
        $playerStatus->setPlayerStatus($status);
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($playerStatus, null));
    }

    public function validPlayerStatusProvider(): array
    {
        return [
            ['Blessé', 0],
            ['Actif', 0],
        ];
    }

    /**
     * @dataProvider invalidPlayerStatusProvider
     */
    public function testInvalidPlayerStatus(string $status, int $expectedViolationsCount): void
    {
        $playerStatus = new PlayerStatus();
        $playerStatus->setPlayerStatus($status);
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($playerStatus, null));
    }

    public function invalidPlayerStatusProvider(): array
    {
        return [
            ['', 1],
            ['1', 1],
        ];
    }
}
