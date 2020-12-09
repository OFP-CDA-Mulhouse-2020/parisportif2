<?php

namespace App\Tests\Unit;

use App\Entity\User;
use DateInterval;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class UserTest extends KernelTestCase
{

    public function testAssertInstanceOfUser()
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
        $this->assertClassHasAttribute('valid', User::class);
        $this->assertClassHasAttribute('validAt', User::class);
        $this->assertClassHasAttribute('suspended', User::class);
        $this->assertClassHasAttribute('suspendedAt', User::class);
        $this->assertClassHasAttribute('deleted', User::class);
        $this->assertClassHasAttribute('deletedAt', User::class);
    }


    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function numberOfViolations(User $user, $groups)
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($user, null, $groups);
        //var_dump($violationList);
        return count($violationList);
    }


    /************************$id**********************************/
    /**
     * @dataProvider idProvider
     */
    public function testId(User $user, $groups, $numberOfViolations)
    {
        $this->assertSame($numberOfViolations, $this->numberOfViolations($user, $groups));
    }

    public function idProvider()
    {
        return [
            [(new User())->setId(2), ['id'], 0],
            [(new User())->setId(23), ['id'], 0],
            [(new User())->setId(258), ['id'], 0],
            [(new User())->setId(-45), ['id'], 1],
        ];
    }

    /************************$user**********************************/



    /**
     * @dataProvider validUserProvider
     */
    public function testValidUser(User $user, $groups, $numberOfViolations)
    {
        $this->assertSame($numberOfViolations, $this->numberOfViolations($user, $groups));
    }

    public function validUserProvider()
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
     */
    public function testInvalidUser(User $user, $groups, $numberOfViolations)
    {
        $this->assertSame($numberOfViolations, $this->numberOfViolations($user, $groups));
    }

    public function invalidUserProvider()
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
     */
    public function testCreationDate(User $user, $groups, $numberOfViolations)
    {
        $this->assertSame($numberOfViolations, $this->numberOfViolations($user, $groups));
    }

    public function createAtProvider()
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
     * @dataProvider isValidProvider
     */
    public function testIsValid(User $user, $groups, $numberOfViolations)
    {
        $this->assertSame($numberOfViolations, $this->numberOfViolations($user, $groups));
    }

    public function isValidProvider()
    {
        return [
            [(new User())->setIsValid(), ['valid'], 0],
            [(new User())->setIsNotValid(), ['valid'], 0],
        ];
    }

    /************************$isSuspended**********************************/

    /**
     * @dataProvider isSuspendedProvider
     */
    public function testIsSuspended(User $user, $groups, $numberOfViolations)
    {
        $this->assertSame($numberOfViolations, $this->numberOfViolations($user, $groups));
    }

    public function isSuspendedProvider()
    {
        return [
            [(new User())->setIsSuspended(), ['suspended'], 0],
            [(new User())->setIsNotSuspended(), ['suspended'], 0],
        ];
    }

    /************************$isDeleted**********************************/

    /**
     * @dataProvider isDeleteProvider
     */
    public function testIsDeleted(User $user, $groups, $numberOfViolations)
    {
        $this->assertSame($numberOfViolations, $this->numberOfViolations($user, $groups));
    }

    public function isDeleteProvider()
    {
        return [
            [(new User())->setIsDeleted(), ['deleted'], 0],
            [(new User())->setIsNotDeleted(), ['deleted'], 0],
        ];
    }
}
