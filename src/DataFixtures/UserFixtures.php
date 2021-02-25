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

        $address1 = $this->getReference(AddressFixtures::ADDRESS_1);
        $wallet1 = $this->getReference(WalletFixtures::WALLET_1);
        $bankAccount1 = $this->getReference(BankAccountFixtures::BANK_ACCOUNT_1);
        $address2 = $this->getReference(AddressFixtures::ADDRESS_2);
        $wallet2 = $this->getReference(WalletFixtures::WALLET_2);
        $bankAccount2 = $this->getReference(BankAccountFixtures::BANK_ACCOUNT_2);
        assert($address1 instanceof Address);
        assert($wallet1 instanceof Wallet);
        assert($bankAccount1 instanceof BankAccount);
        assert($address2 instanceof Address);
        assert($wallet2 instanceof Wallet);
        assert($bankAccount2 instanceof BankAccount);

        $user1 = new User();
        $user1->setFirstName('ladji')
            ->setLastName('cda')
            ->setEmail('ladji.cda@test.com')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user1,
                'M1cdacda8'
            ))
            ->setBirthDate(DateTime::createFromFormat('Y-m-d', '1995-12-12'))
            ->setCreateAt(new DateTime())
            ->activate()
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setWallet($wallet1)
            ->setBankAccount($bankAccount1)
            ->setAddress($address1);

        $manager->persist($user1);



        $user2 = new User();
        $user2->setFirstName('mohamed')
            ->setLastName('cda')
            ->setEmail('mohamed.cda@test.com')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user2,
                'M1cdacda8'
            ))
            ->setBirthDate(DateTime::createFromFormat('Y-m-d', '1995-12-12'))
            ->setCreateAt(new DateTime())
            ->activate()
            ->setRoles(['ROLE_USER'])
            ->setWallet($wallet2)
            ->setBankAccount($bankAccount2)
            ->setAddress($address2);

        $manager->persist($user2);



        $user3 = new User();
        $user3->setFirstName('daniel')
            ->setLastName('cda')
            ->setEmail('daniel.cda@test.com')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user3,
                'M1cdacda8'
            ))
            ->setBirthDate(DateTime::createFromFormat('Y-m-d', '1995-12-12'))
            ->setCreateAt(new DateTime())
            ->setRoles(['ROLE_USER']);

        $manager->persist($user3);

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
