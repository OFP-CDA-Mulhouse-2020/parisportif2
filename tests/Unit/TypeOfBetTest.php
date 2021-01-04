<?php

namespace App\Tests\Unit;

use App\Entity\TypeOfBet;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class TypeOfBetTest extends KernelTestCase
{
    public function testAssertInstanceOfTypeOfBet(): void
    {
        $typeOfBet = new TypeOfBet();
        $this->assertInstanceOf(TypeOfBet::class, $typeOfBet);
        $this->assertClassHasAttribute('id', TypeOfBet::class);
        $this->assertClassHasAttribute('betType', TypeOfBet::class);
    }

    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(TypeOfBet $typeOfBet, ?array $groups): int
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($typeOfBet, null, $groups);
        //var_dump($violationList);
        return count($violationList);
    }

    /**
     * @dataProvider validTypeOfBetProvider
     */
    public function testValidTypeOfBet(string $betType, int $expectedViolationsCount): void
    {
        $typeOfBet = new TypeOfBet();
        $typeOfBet->setTypeOfBet($betType);
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($typeOfBet, null));
    }

    public function validTypeOfBetProvider(): array
    {
        return [
            ['1N2', 0],
            ['under-over', 0],
        ];
    }

    /**
     * @dataProvider invalidTypeOfBetProvider
     */
    public function testInvalidTypeOfBet(string $betType, int $expectedViolationsCount): void
    {
        $typeOfBet = new TypeOfBet();
        $typeOfBet->setTypeOfBet($betType);
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($typeOfBet, null));
    }

    public function invalidTypeOfBetProvider(): array
    {
        return [
            ['', 1],
            ['1', 1],
        ];
    }
}
