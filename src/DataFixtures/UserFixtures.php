<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\BankAccount;
use App\Entity\Cart;
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
        $address2 = $this->getReference(AddressFixtures::ADDRESS_2);
        $address3 = $this->getReference(AddressFixtures::ADDRESS_3);

        $wallet1 = $this->getReference(WalletFixtures::WALLET_1);
        $wallet2 = $this->getReference(WalletFixtures::WALLET_2);
        $wallet3 = $this->getReference(WalletFixtures::WALLET_3);

        $bankAccount1 = $this->getReference(BankAccountFixtures::BANK_ACCOUNT_1);
        $bankAccount2 = $this->getReference(BankAccountFixtures::BANK_ACCOUNT_2);
        $bankAccount3 = $this->getReference(BankAccountFixtures::BANK_ACCOUNT_3);

        $cart1 = $this->getReference(CartFixtures::CART_1);

        assert($address1 instanceof Address);
        assert($address2 instanceof Address);
        assert($address3 instanceof Address);

        assert($wallet1 instanceof Wallet);
        assert($wallet2 instanceof Wallet);
        assert($wallet3 instanceof Wallet);

        assert($bankAccount1 instanceof BankAccount);
        assert($bankAccount2 instanceof BankAccount);
        assert($bankAccount3 instanceof BankAccount);

        assert($cart1 instanceof Cart);

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
            ->setRoles(['ROLE_USER','ROLE_SUPER_ADMIN'])
            ->setWallet($wallet1)
            ->setBankAccount($bankAccount1)
            ->setAddress($address1)
            ->setCart($cart1)
        ;

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
            ->setRoles(["ROLE_USER"])
            ->setWallet($wallet2)
            ->setBankAccount($bankAccount2)
            ->setAddress($address2);

        $manager->persist($user2);


        $user3 = new User();
        $user3->setFirstName('mathieu')
            ->setLastName('cda')
            ->setEmail('mathieu.test@cda.com')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user3,
                'M1cdacda8'
            ))
            ->setBirthDate(DateTime::createFromFormat('Y-m-d', '1995-12-12'))
            ->setCreateAt(new DateTime())
            ->activate()
            ->setRoles(["ROLE_USER","ROLE_ADMIN"])
            ->setWallet($wallet3)
            ->setBankAccount($bankAccount3)
            ->setAddress($address3);

        $manager->persist($user3);


        $user4 = new User();
        $user4->setFirstName('daniel')
            ->setLastName('cda')
            ->setEmail('daniel.cda@test.com')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user4,
                'M1cdacda8'
            ))
            ->setBirthDate(DateTime::createFromFormat('Y-m-d', '1995-12-12'))
            ->setCreateAt(new DateTime())
            ->setRoles(['ROLE_USER']);

        $manager->persist($user4);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            WalletFixtures::class,
            BankAccountFixtures::class,
            AddressFixtures::class,
            CartFixtures::class,
        ];
    }
}
