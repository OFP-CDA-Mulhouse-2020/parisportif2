<?php

namespace App\Tests;

use App\Entity\User;
use DateInterval;
use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
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
        $this->assertClassHasAttribute('createDate', User::class);
        $this->assertClassHasAttribute('userValidation', User::class);
        $this->assertClassHasAttribute('userValidationDate', User::class);
        $this->assertClassHasAttribute('userSuspended', User::class);
        $this->assertClassHasAttribute('userSuspendedDate', User::class);
        $this->assertClassHasAttribute('userDeleted', User::class);
        $this->assertClassHasAttribute('userDeletedDate', User::class);
    }

/************************$id**********************************/
    /**
     * @dataProvider additionProviderId
     */
    public function testSetId($id)
    {
        $user = new User();
        $user->setId($id);
        $this->assertSame($id, $user->getId());
    }

    public function additionProviderId()
    {
        return [
          [1],
          [23],
          [258],
          [455]
        ];
    }

/************************$lastName**********************************/



/************************$firstName**********************************/



/************************$email**********************************/

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

        return count($violationList);
    }

    /**
     * @dataProvider validUserProvider
     */
    public function testValidUser(User $user, $groups, $numberOfViolations)
    {
       // var_dump($user);
        $this->assertSame($numberOfViolations, $this->numberOfViolations($user, $groups));
    }

    public function validUserProvider()
    {
        return [
            [User::build('daniel', 'test', 'daniel@test.fr', 'M1cdacda', '1995-12-12'), null, 0],
            [User::build('Jean-Pierre', 'V', null, null, null), ['username'], 0],
            [User::build('V', 'Jean-Pierre', null, null, null), ['username'], 0],
            [User::build("j'ai trente caractères", "j'ai trente caractères", null, null, null), ['username'], 0],
            [User::build(null, null, 'daniel@test.fr', null, null), ['email'], 0],
            [User::build(null, null, 'test@test.com', null, null), ['email'], 0],
            [User::build(null, null, 'lalalla@d.fr', null, null), ['email'], 0],
            [User::build(null, null, 'g@te.de', null, null), ['email'], 0],
            [User::build(null, null, null, 'Jean2825', null), ['password'], 0],
            [User::build(null, null, null, 'T4G5h2f3R0aaaa', null), ['password'], 0],
            [User::build(null, null, null, 'Macron41Paris', null), ['password'], 0],
            [User::build(null, null, null, 'M1cdacda', null), ['password'], 0]
        ];
    }


    /**
     * @dataProvider invalidUserProvider
     */
    public function testInvalidUser(User $user, $groups, $numberOfViolations)
    {
       // var_dump($user);
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
            [User::build(null, null, null, 'MacronParis', null), ['password'], 1]
        ];
    }


/************************$password**********************************/

/************************$birthDate**********************************/
    /**
     * @dataProvider additionProviderBirthDate
     */
    public function testSetBirthDate($birthDate)
    {
        $user = new User();
        $user->setBirthDate($birthDate);

        $this->assertEquals($birthDate, $user->getBirthDate());
    }

    public function additionProviderBirthDate()
    {
        return [
          [DateTime::createFromFormat('Y-m-d', '2002-11-12')],
          [DateTime::createFromFormat('Y-m-d', '1995-12-12')],
          [(new DateTime())->sub(new DateInterval('P18Y'))]
        ];
    }

    /**
     * @dataProvider additionProviderFailBirthDate
     */
    public function testFailSetBirthDate($birthDate)
    {
        $this->expectException(InvalidArgumentException::class);

        $user = new User();
        $user->setBirthDate($birthDate);
    }

    public function additionProviderFailBirthDate()
    {
        return [
            [DateTime::createFromFormat('Y-m-d', '2012-11-12')],
            [DateTime::createFromFormat('Y-m-d', '2020-12-12')],
            [new DateTime()]
        ];
    }

/************************$createDate**********************************/
    /**
     * @dataProvider additionProviderCreateDate
     */
    public function testSetCreateDate($createDate)
    {
        $user = new User();
        $user->setCreateDate($createDate);

        $this->assertEquals($createDate, $user->getCreateDate());
    }

    public function additionProviderCreateDate()
    {
        return [
          [DateTime::createFromFormat('Y-m-d', '2002-11-12')],
          [DateTime::createFromFormat('Y-m-d', '1995-12-12')],
          [(new DateTime())->sub(new DateInterval('P18Y'))]
        ];
    }

    /**
     * @dataProvider additionProviderFailCreateDate
     */
    public function testFailSetCreateDate($createDate)
    {
        $this->expectException(InvalidArgumentException::class);

        $user = new User();
        $user->setCreateDate($createDate);
    }

    public function additionProviderFailCreateDate()
    {
        return [
          [DateTime::createFromFormat('Y-m-d', '2022-11-12')],
          [(new DateTime())->add(new DateInterval('P1D'))]
        ];
    }

/************************$userValidation**********************************/
    /**
     * @dataProvider additionProviderUserValidation
     */
    public function testSetUserValidation($userValidation)
    {
        $user = new User();
        $user->setUserValidation($userValidation);
        $this->assertSame($userValidation, $user->getUserValidation());
    }

    public function additionProviderUserValidation()
    {
        return [
          [true],
          [false]
        ];
    }

/************************$userValidationDate**********************************/
    /**
     * @dataProvider additionProviderUserValidationDate
     */
    public function testSetUserValidationDate($userValidationDate)
    {
        $user = new User();
        $user->setUserValidationDate($userValidationDate);

        $this->assertEquals($userValidationDate, $user->getUserValidationDate());
    }

    public function additionProviderUserValidationDate()
    {
        return [
          [DateTime::createFromFormat('Y-m-d', '2012-11-12')],
          [DateTime::createFromFormat('Y-m-d', '2020-10-12')],
          [new DateTime()],
          [null]
        ];
    }

    /**
     * @dataProvider additionProviderFailUserValidationDate
     */
    public function testFailSetUserValidationDate($userValidationDate)
    {
        $this->expectException(InvalidArgumentException::class);

        $user = new User();
        $user->setUserValidationDate($userValidationDate);
    }

    public function additionProviderFailUserValidationDate()
    {
        return [
          [DateTime::createFromFormat('Y-m-d', '2022-11-12')],
          [(new DateTime())->add(new DateInterval('P1D'))]
        ];
    }

/************************$userSuspended**********************************/
    /**
     * @dataProvider additionProviderUserSuspended
     */
    public function testSetUserSuspended($userSuspended)
    {
        $user = new User();
        $user->setUserSuspended($userSuspended);
        $this->assertSame($userSuspended, $user->getUserSuspended());
    }

    public function additionProviderUserSuspended()
    {
        return [
          [true],
          [false]
        ];
    }

/************************$userSuspendedDate**********************************/
    /**
     * @dataProvider additionProviderUserSuspendedDate
     */
    public function testSetUserSuspendedDate($userSuspendedDate)
    {
        $user = new User();
        $user->setUserSuspendedDate($userSuspendedDate);

        $this->assertEquals($userSuspendedDate, $user->getUserSuspendedDate());
    }

    public function additionProviderUserSuspendedDate()
    {
        return [
          [DateTime::createFromFormat('Y-m-d', '2012-11-12')],
          [DateTime::createFromFormat('Y-m-d', '2020-10-12')],
          [new DateTime()],
          [null]
        ];
    }

    /**
     * @dataProvider additionProviderFailUserSuspendedDate
     */
    public function testFailSetUserSuspendedDate($userSuspendedDate)
    {
        $this->expectException(InvalidArgumentException::class);

        $user = new User();
        $user->setUserSuspendedDate($userSuspendedDate);
    }

    public function additionProviderFailUserSuspendedDate()
    {
        return [
          [DateTime::createFromFormat('Y-m-d', '2022-11-12')],
          [(new DateTime())->add(new DateInterval('P1D'))]
        ];
    }

/************************$userDeleted**********************************/
    /**
     * @dataProvider additionProviderUserDeleted
     */
    public function testSetUserDeleted($userDeleted)
    {
        $user = new User();
        $user->setUserDeleted($userDeleted);
        $this->assertSame($userDeleted, $user->getUserDeleted());
    }

    public function additionProviderUserDeleted()
    {
        return [
          [true],
          [false]
        ];
    }

/************************$userDeletedDate**********************************/
    /**
     * @dataProvider additionProviderUserDeletedDate
     */
    public function testSetUserDeletedDate($userDeletedDate)
    {
        $user = new User();
        $user->setUserDeletedDate($userDeletedDate);

        $this->assertEquals($userDeletedDate, $user->getUserDeletedDate());
    }

    public function additionProviderUserDeletedDate()
    {
        return [
          [DateTime::createFromFormat('Y-m-d', '2012-11-12')],
          [DateTime::createFromFormat('Y-m-d', '2020-10-12')],
          [new DateTime()],
          [null]
        ];
    }

    /**
     * @dataProvider additionProviderFailUserDeletedDate
     */
    public function testFailSetUserDeletedDate($userDeletedDate)
    {
        $this->expectException(InvalidArgumentException::class);

        $user = new User();
        $user->setUserDeletedDate($userDeletedDate);
    }

    public function additionProviderFailUserDeletedDate()
    {
        return [
          [DateTime::createFromFormat('Y-m-d', '2022-11-12')],
          [(new DateTime())->add(new DateInterval('P1D'))]
        ];
    }
}
