<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture
{
    public const ADDRESS_1 = 'address_1';
    public const ADDRESS_2 = 'address_2';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $address1 = new Address();
        $address1->setAddressNumberAndStreet('8 rue des champs')
                ->setZipCode(75000)
                ->setCity('Paris')
                ->setCountry('France');
        $manager->persist($address1);

        $address2 = new Address();
        $address2->setAddressNumberAndStreet('10 rue des champs')
            ->setZipCode(75000)
            ->setCity('Paris')
            ->setCountry('France');
        $manager->persist($address2);


        $manager->flush();

        $this->addReference(self::ADDRESS_1, $address1);
        $this->addReference(self::ADDRESS_2, $address2);
    }
}
