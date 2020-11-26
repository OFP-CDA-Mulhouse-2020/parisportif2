<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setLogin('danytest')
            ->setMail('daniel.cda@test.com')
            ->setPassword('123456789');

        $manager->persist($user);

        $user = new User();
        $user->setLogin('ladjitest')
            ->setMail('ladji.cda@test.com')
            ->setPassword('123456789');

        $manager->persist($user);

        $manager->flush();
    }
}
