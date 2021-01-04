<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setFirstName('danytest')
            ->setLastName('danytest')
            ->setEmail('daniel.cda@test.com')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'M1cdacda8'
            ))
            ->setBirthDate(DateTime::createFromFormat('Y-m-d', '1995-12-12'))
            ->setCreateAt(new DateTime())
            ->setRoles(['ROLE_USER']);



        $manager->persist($user);

        $user = new User();
        $user->setFirstName('ladjitest')
            ->setLastName('ladjitest')
            ->setEmail('ladji.cda@test.com')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'M1cdacda8'
            ))
            ->setBirthDate(DateTime::createFromFormat('Y-m-d', '1995-12-12'))
            ->setCreateAt(new DateTime())
            ->setRoles(['ROLE_USER']);


        $manager->persist($user);

        $manager->flush();
    }
}
