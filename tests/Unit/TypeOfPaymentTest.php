<?php

namespace App\Tests\Unit;

use App\Entity\TypeOfPayment;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TypeOfPaymentTest extends KernelTestCase
{
    public function testAssertInstanceTypeOfPayment(): void
    {
        $typeOfPayment = new TypeOfPayment();
        $this->assertInstanceOf(TypeOfPayment::class, $typeOfPayment);
        $this->assertClassHasAttribute('id', TypeOfPayment::class);
        $this->assertClassHasAttribute('typeOfPayment', TypeOfPayment::class);
    }

    public function testValidTypeOfPayment(): void
    {
        $typeOfPayment = new TypeOfPayment();

        $typeOfPayment->setTypeOfPayment('Réel');
        $this->assertSame('Réel', $typeOfPayment->getTypeOfPayment());

        $typeOfPayment->setTypeOfPayment('Virtuel');
        $this->assertSame('Virtuel', $typeOfPayment->getTypeOfPayment());
    }
}
