<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture
{
    public const ADDRESS_USER_2 = 'user2_address';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $address = new Address();
        $address->setAddressNumberAndStreet('8 rue des champs')
                ->setZipCode(75000)
                ->setCity('Paris')
                ->setCountry('France');
        $manager->persist($address);

        $manager->flush();

        $this->addReference(self::ADDRESS_USER_2, $address);
    }
}
