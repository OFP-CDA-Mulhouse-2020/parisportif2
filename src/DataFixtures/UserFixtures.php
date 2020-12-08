<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setFirstName('danytest')
            ->setLastName('danytest')
            ->setEmail('daniel.cda@test.com')
            ->setPassword('M1cdacda8')
            ->setBirthDate(DateTime::createFromFormat('Y-m-d', '1995-12-12'))
            ->setCreateAt(new DateTime())
            ->setIsValid(false)
            ->setIsSuspended(true)
            ->setIsDeleted(false);

        $manager->persist($user);

        $user = new User();
        $user->setFirstName('ladjitest')
            ->setLastName('ladjitest')
            ->setEmail('ladji.cda@test.com')
            ->setPassword('M1cdacda8')
            ->setBirthDate(DateTime::createFromFormat('Y-m-d', '1995-12-12'))
            ->setCreateAt(new DateTime())
            ->setIsValid(false)
            ->setIsSuspended(true)
            ->setIsDeleted(false);

        $manager->persist($user);

        $manager->flush();
    }
}
