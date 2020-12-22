<?php

namespace App\Tests\Unit;

use App\Entity\Address;
use App\Entity\BankAccount;
use App\Entity\Cart;
use App\Entity\User;
use App\Entity\Wallet;
use DateInterval;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class UserTest extends KernelTestCase
{

    public function testAssertInstanceOfUser(): void
    {
        $user = new User();
        $this->assertInstanceOf(User::class, $user);
        $this->assertClassHasAttribute('id', User::class);
        $this->assertClassHasAttribute('lastName', User::class);
        $this->assertClassHasAttribute('firstName', User::class);
        $this->assertClassHasAttribute('email', User::class);
        $this->assertClassHasAttribute('password', User::class);
        $this->assertClassHasAttribute('birthDate', User::class);
        $this->assertClassHasAttribute('createAt', User::class);
        $this->assertClassHasAttribute('active', User::class);
        $this->assertClassHasAttribute('activeAt', User::class);
        $this->assertClassHasAttribute('suspended', User::class);
        $this->assertClassHasAttribute('suspendedAt', User::class);
        $this->assertClassHasAttribute('deleted', User::class);
        $this->assertClassHasAttribute('deletedAt', User::class);
        $this->assertClassHasAttribute('address', User::class);
        $this->assertClassHasAttribute('wallet', User::class);
        $this->assertClassHasAttribute('bankAccount', User::class);
    }

    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function getViolationsCount(User $user, ?array $groups): int
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($user, null, $groups);
        //var_dump($violationList);
        return count($violationList);
    }

    /************************$user**********************************/


    /**
     * @dataProvider validUserProvider
     * @param User $user
     * @param array $groups
     * @param int $expectedViolationsCount
     */
    public function testValidUser(User $user, ?array $groups, int $expectedViolationsCount): void
    {
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($user, $groups));
    }

    public function validUserProvider(): array
    {
        return [
            [User::build('daniel', 'test', 'daniel@test.fr', 'M1cdacda', '1995-12-12'), ['register'], 0],
            [User::build('Jean-Pierre', 'V', null, null, null), ['username'], 0],
            [User::build('V', 'Jean-Pierre', null, null, null), ['username'], 0],
            [User::build("j'ai trente caractères", "j'ai trente caractères", null, null, null), ['username'], 0],
            [User::build(null, null, 'daniel@test.fr', 'Jean2825', null), ['login'], 0],
            [User::build(null, null, 'test@test.com', 'T4G5h2f3R0aaaa', null), ['login'], 0],
            [User::build(null, null, 'lalalla@d.fr', 'Macron41Paris', null), ['login'], 0],
            [User::build(null, null, 'g@te.de', 'M1cdacda', null), ['login'], 0],
            [User::build(null, null, null, null, '2002-11-12'), ['birthDate'], 0],
            [User::build(null, null, null, null, '1995-12-12'), ['birthDate'], 0],
        ];
    }

    /**
     * @dataProvider invalidUserProvider
     * @param User $user
     * @param array $groups
     * @param int $expectedViolationsCount
     */
    public function testInvalidUser(User $user, array $groups, int $expectedViolationsCount): void
    {
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($user, $groups));
    }

    public function invalidUserProvider(): array
    {
        return [
            [User::build(
                "J'ai plus de trente caratères donc ça ne marche pas",
                'V',
                null,
                null,
                null
            ), ['username'], 1],
            [User::build("j'ai trente caractères", 'Macron de Paris 17', null, null, null), ['username'], 1],
            [User::build(null, null, null, null, null), ['username'], 2],
            [User::build(null, null, 'testtest.com', null, null), ['email'], 1],
            [User::build(null, null, 'lalalla@dfr', null, null), ['email'], 1],
            [User::build(null, null, '@te.de', null, null), ['email'], 1],
            [User::build(null, null, '', null, null), ['email'], 1],
            [User::build(null, null, 'gmail-dudu.*@tetest.fdsfd..de', null, null), ['email'], 1],
            [User::build(null, null, null, 'jean2825', null), ['password'], 1],
            [User::build(null, null, null, '123456rt', null), ['password'], 1],
            [User::build(null, null, null, 'MacronParis', null), ['password'], 1],
            [User::build(null, null, null, null, '2012-11-12'), ['birthDate'], 1],
            [User::build(null, null, null, null, '2020-12-12'), ['birthDate'], 1],
        ];
    }

    /************************$createAt**********************************/

    /**
     * @dataProvider createAtProvider
     * @param User $user
     * @param array $groups
     * @param int $expectedViolationsCount
     */
    public function testCreationDate(User $user, array $groups, int $expectedViolationsCount): void
    {
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($user, $groups));
    }

    public function createAtProvider(): array
    {
        return [
            [(new User())->setCreateAt(DateTime::createFromFormat('Y-m-d', '2019-12-12')), ['createAt'], 0],
            [(new User())->setCreateAt(new DateTime()), ['createAt'], 0],
            [(new User())->setCreateAt(DateTime::createFromFormat('Y-m-d', '2022-12-12')), ['createAt'], 1],
            [(new User())->setCreateAt((new DateTime())->add(new DateInterval('P1D'))), ['createAt'], 1],
        ];
    }

    /************************$isValid**********************************/

    /**
     * @dataProvider activateProvider
     * @param User $user
     * @param array $groups
     * @param int $expectedViolationsCount
     * @param bool $expectedActiveValue
     * @param ?DateTime $expectedActiveAtValue
     */
    public function testActivate(
        User $user,
        array $groups,
        int $expectedViolationsCount,
        bool $expectedActiveValue,
        ?DateTime $expectedActiveAtValue
    ): void {
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($user, $groups));
        $this->assertSame($expectedActiveValue, $user->isActive());
        $this->assertEqualsWithDelta($expectedActiveAtValue, $user->getActiveAt(), 1);
    }

    public function activateProvider(): array
    {
        return [
            [(new User())->activate(), ['active'], 0, true, new DateTime()],
            [(new User())->deactivate(), ['active'], 0, false, null],
        ];
    }

    /************************$isSuspended**********************************/

    /**
     * @dataProvider suspendProvider
     * @param User $user
     * @param array $groups
     * @param int $expectedViolationsCount
     * @param bool $expectedSuspendedValue
     * @param ?DateTime $expectedsuspendedAtValue
     */
    public function testSuspend(
        User $user,
        array $groups,
        int $expectedViolationsCount,
        bool $expectedSuspendedValue,
        ?DateTime $expectedsuspendedAtValue
    ): void {
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($user, $groups));
        $this->assertSame($expectedSuspendedValue, $user->isSuspended());
        $this->assertEqualsWithDelta($expectedsuspendedAtValue, $user->getSuspendedAt(), 1);
    }

    public function suspendProvider(): array
    {
        return [
            [(new User())->suspend(), ['suspend'], 0, true, new DateTime()],
            [(new User())->unsuspended(), ['suspend'], 0, false, null],
        ];
    }

    /************************$isDeleted**********************************/

    /**
     * @dataProvider deleteProvider
     * @param User $user
     * @param array $groups
     * @param int $expectedViolationsCount
     * @param bool $expectedDeletedValue
     * @param ?DateTime $expectedDeletedAtValue
     */
    public function testDelete(
        User $user,
        array $groups,
        int $expectedViolationsCount,
        bool $expectedDeletedValue,
        ?DateTime $expectedDeletedAtValue
    ): void {
        $this->assertSame($expectedViolationsCount, $this->getViolationsCount($user, $groups));
        $this->assertSame($expectedDeletedValue, $user->isDeleted());
        $this->assertEqualsWithDelta($expectedDeletedAtValue, $user->getDeletedAt(), 1);
    }

    public function deleteProvider(): array
    {
        return [
            [(new User())->delete(), ['delete'], 0, true, new DateTime()],
            [(new User())->undelete(), ['delete'], 0, false, null],
        ];
    }


    public function testValidAddress(): void
    {
        $address = Address::build('8 rue des champs', 68000, 'Colmar', 'France');
        $user = new User();
        $user->setAddress($address);
        $this->assertInstanceOf(Address::class, $user->getAddress());
        $this->assertSame(0, $this->getViolationsCount($user, ['address']));
    }

    public function testInvalidAddress(): void
    {
        $address = Address::build('8 rue des champs', 68000, 'Colmar', '');
        $user = new User();
        $user->setAddress($address);
        $this->assertInstanceOf(Address::class, $user->getAddress());
        $this->assertSame(1, $this->getViolationsCount($user, ['address']));
    }

    public function testValidBankAccount(): void
    {
        $bankAccount = BankAccount::build('FR7630006000011234567890189', 'BNPAFRPPTAS');
        $user = new User();
        $user->setBankAccount($bankAccount);
        $this->assertInstanceOf(BankAccount::class, $user->getBankAccount());
        $this->assertSame(0, $this->getViolationsCount($user, ['bankAccount']));
    }

    public function testInvalidBankAccount(): void
    {
        $bankAccount = BankAccount::build('1', '1');
        $user = new User();
        $user->setBankAccount($bankAccount);
        $this->assertInstanceOf(BankAccount::class, $user->getBankAccount());
        $this->assertSame(2, $this->getViolationsCount($user, ['bankAccount']));
    }

    public function testValidWallet(): void
    {
        $wallet = new Wallet();
        $wallet->initializeWallet(true);
        $user = new User();
        $user->setWallet($wallet);
        $this->assertInstanceOf(Wallet::class, $user->getWallet());
        $this->assertSame(0, $this->getViolationsCount($user, ['wallet']));
    }

    public function testInvalidWallet(): void
    {
        $wallet = new Wallet();
        $user = new User();
        $user->setWallet($wallet);
        $this->assertInstanceOf(Wallet::class, $user->getWallet());

        $this->assertSame(3, $this->getViolationsCount($user, ['wallet']));
    }

    public function testValidCart(): void
    {
        $cart = new Cart();
        $user = new User();
        $user->setCart($cart);
        $this->assertInstanceOf(Cart::class, $user->getCart());
        $this->assertSame(0, $this->getViolationsCount($user, ['cart']));
    }
}
