<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\BankAccount;
use App\Entity\User;
use App\Entity\Wallet;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
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
        $user->setFirstName('daniel')
            ->setLastName('cda')
            ->setEmail('daniel.cda@test.com')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'M1cdacda8'
            ))
            ->setBirthDate(DateTime::createFromFormat('Y-m-d', '1995-12-12'))
            ->setCreateAt(new DateTime())
            ->setRoles(['ROLE_USER']);



        $manager->persist($user);

        $address = $this->getReference(AddressFixtures::ADDRESS_USER_2);
        $wallet = $this->getReference(WalletFixtures::WALLET_USER_2);
        $bankAccount = $this->getReference(BankAccountFixtures::BANK_ACCOUNT_USER_2);
        assert($address instanceof Address);
        assert($wallet instanceof Wallet);
        assert($bankAccount instanceof BankAccount);


        $user = new User();
        $user->setFirstName('ladji')
            ->setLastName('cda')
            ->setEmail('ladji.cda@test.com')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'M1cdacda8'
            ))
            ->setBirthDate(DateTime::createFromFormat('Y-m-d', '1995-12-12'))
            ->setCreateAt(new DateTime())
            ->activate()
            ->setRoles(['ROLE_USER'])
            ->setWallet($wallet)
            ->setBankAccount($bankAccount)
            ->setAddress($address);


        $manager->persist($user);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            WalletFixtures::class,
            BankAccountFixtures::class,
            AddressFixtures::class,

        ];
    }
}
