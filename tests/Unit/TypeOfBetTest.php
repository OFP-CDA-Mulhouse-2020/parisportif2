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
        $this->assertClassHasAttribute('typeOfBet', TypeOfBet::class);
    }
}
