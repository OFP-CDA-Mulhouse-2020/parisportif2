<?php

namespace App\Tests;

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


    /************ $id ***************/
    /**
     * @dataProvider additionProviderId
     */
    public function testSetId($id)
    {
        $address = new Address();
        $address->setId($id);
        $this->assertSame($id, $address->getId());
    }

    public function additionProviderId()
    {
        return [
            [1],
            [23],
            [258],
            [455]
        ];
    }


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
            [Address::build('12 rue du test', 77777, 'TestCity', 'Testland'), null, 0]
            // [Address::build('12 rue du test', null, null, null), ['addressNumberAndStreet'], 0]
        ];
    }
}
