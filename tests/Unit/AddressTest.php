<?php

namespace App\Tests\Unit;

use App\Entity\Address;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class AddressTest extends KernelTestCase
{
    public function testAssertInstanceOfAddress()
    {
        $address = new Address();
        $this->assertInstanceOf(Address::class, $address);
        $this->assertClassHasAttribute('id', Address::class);
        $this->assertClassHasAttribute('addressNumberAndStreet', Address::class);
        $this->assertClassHasAttribute('zipCode', Address::class);
        $this->assertClassHasAttribute('city', Address::class);
        $this->assertClassHasAttribute('country', Address::class);
    }


    /************ Kernel ***************/


    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function numberOfViolations(Address $address, $groups)
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($address, null, $groups);

        return count($violationList);
    }

    /**
     * @dataProvider validAddressProvider
     */

    public function testValidAddress(Address $address, $groups, $numberOfViolations)
    {
        $this->assertSame($numberOfViolations, $this->numberOfViolations($address, $groups));
    }

    public function validAddressProvider()
    {
        return [
            [Address::build('12 rue du test', 77777, 'TestCity', 'Testland'), ['adress'], 0]
            // [Address::build('12 rue du test', null, null, null), ['addressNumberAndStreet'], 0]
        ];
    }


    /**
     * @dataProvider invalidAddressProvider
     */
    public function testInvalidAddress(Address $address, $groups, $numberOfViolations)
    {
        $this->assertSame($numberOfViolations, $this->numberOfViolations($address, $groups));
    }

    public function invalidAddressProvider()
    {
        return [
            [Address::build(null, null, null, null), ['address'], 4],
            [Address::build("rue vaugirard", null, null, null), ['addressNumberAndStreet'], 1],
            [Address::build("1", null, null, null), ['addressNumberAndStreet'], 1],
            [Address::build(null, 12, null, null), ['zipCode'], 1],
            [Address::build(null, 123456, null, null), ['zipCode'], 1],
            [Address::build(null, null, "1555jfdjf", null), ['city'], 1],
            [Address::build(null, null, "le nom de la ville à plus de quarante caractères", null), ['city'], 1],
            [Address::build(null, null, null, "1555jfdjf"), ['country'], 1],
            [Address::build(null, null, null, "le nom du pays à plus de quarante caractères"), ['country'], 1]

        ];
    }
}
