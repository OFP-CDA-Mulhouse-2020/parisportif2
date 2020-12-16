<?php

namespace App\Tests;

use App\Entity\TypeOfPayment;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TypeOfPaymentTest extends KernelTestCase
{
    public function testAssertInstanceTypeOfPayment()
    {
        $typeOfPayment = new TypeOfPayment();
        $this->assertInstanceOf(TypeOfPayment::class, $typeOfPayment);
        $this->assertClassHasAttribute('id', TypeOfPayment::class);
        $this->assertClassHasAttribute('typeOfPayment', TypeOfPayment::class);
    }

    public function testValidTypeOfPayment()
    {
        $typeOfPayment = new TypeOfPayment();

        $typeOfPayment->setRealTypeOfPayment();
        $this->assertSame('Réel', $typeOfPayment->getTypeOfPayment());

        $typeOfPayment->setVirtualTypeOfPayment();
        $this->assertSame('Virtuel', $typeOfPayment->getTypeOfPayment());
    }
}
