<?php

namespace App\Tests;

use App\Entity\User2;
use DateInterval;
use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class User2Test extends TestCase
{
    public function testAssertInstanceOfUser2()
    {
        $this->user = new User2();
        $this->assertInstanceOf(User2::class, $this->user);
        $this->assertClassHasAttribute('id', User2::class);
        $this->assertClassHasAttribute('lastName', User2::class);
        $this->assertClassHasAttribute('firstName', User2::class);
        $this->assertClassHasAttribute('mail', User2::class);
        $this->assertClassHasAttribute('password', User2::class);
        $this->assertClassHasAttribute('birthDate', User2::class);
        $this->assertClassHasAttribute('createDate', User2::class);
        $this->assertClassHasAttribute('userValidation', User2::class);
        $this->assertClassHasAttribute('userValidationDate', User2::class);
        $this->assertClassHasAttribute('userSuspended', User2::class);
        $this->assertClassHasAttribute('userSuspendedDate', User2::class);
        $this->assertClassHasAttribute('userDeleted', User2::class);
        $this->assertClassHasAttribute('userDeletedDate', User2::class);
    }

/************************$id**********************************/
    /**
     * @dataProvider additionProviderId
     */
    public function testSetId($id)
    {
        $this->user = new User2();
        $this->user->setId($id);
        $this->assertSame($id, $this->user->getId());
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
    /**
     * @dataProvider additionProviderLastName
     */
    public function testSetLastName($lastName)
    {
        $this->user = new User2();
        $this->user->setLastName($lastName);

        $this->assertSame($lastName, $this->user->getLastName());
    }

    public function additionProviderLastName()
    {
        return [
          ['Jean-Pierre'],
          ['V'],
          ["j'ai trente caractères"]

        ];
    }

    /**
     * @dataProvider additionProviderFailLastName
     */
    public function testFailSetLastName($lastName)
    {
        $this->expectException(InvalidArgumentException::class);

        $this->user = new User2();
        $this->user->setLastName($lastName);
    }

    public function additionProviderFailLastName()
    {
        return [
          ["J'ai plus de trente caratères donc ça ne marche pas"],
          [''],
          ['Macron de Paris 17']
        ];
    }

/************************$firstName**********************************/
    /**
     * @dataProvider additionProviderFirstName
     */
    public function testSetFirstName($firstName)
    {
        $this->user = new User2();
        $this->user->setFirstName($firstName);

        $this->assertSame($firstName, $this->user->getFirstName());
    }

    public function additionProviderFirstName()
    {
        return [
          ['Jean-Pierre'],
          ['V'],
          ['Macron de Paris']
        ];
    }

    /**
     * @dataProvider additionProviderFailFirstName
     */
    public function testFailSetFirstName($firstName)
    {
        $this->expectException(InvalidArgumentException::class);

        $this->user = new User2();
        $this->user->setFirstName($firstName);
    }

    public function additionProviderFailFirstName()
    {
        return [
          ["J'ai plus de trente caratères donc ça ne marche pas"],
          [''],
          ['Macron de Paris 17']
        ];
    }

/************************$mail**********************************/
    /**
     * @dataProvider additionProviderMail
     */
    public function testSetMail($mail)
    {
        $this->user = new User2();
        $this->user->setMail($mail);

        $this->assertSame($mail, $this->user->getMail());
    }

    public function additionProviderMail()
    {
        return [
            ['test@test.com'],
            ['lalalla@d.fr'],
            ['g@te.de'],
            ['gmail-dudu@tetest.fdsfd.de']
        ];
    }

    /**
     * @dataProvider additionProviderFailMail
     */
    public function testSetFailMail($mail)
    {
        $this->expectException(InvalidArgumentException::class);

        $this->user = new User2();
        $this->user->setMail($mail);
    }

    public function additionProviderFailMail()
    {
        return [
            ['testtest.com'],
            ['lalalla@dfr'],
            ['@te.de'],
            ['gmail-dudu.*@tetest.fdsfd.de']
        ];
    }

/************************$password**********************************/
    /**
     * @dataProvider additionProviderPassword
     */
    public function testSetPassword($password)
    {
        $this->user = new User2();
        $this->user->setPassword($password);
        $passhash = $this->user->getPassword();
        password_verify($password, $passhash);
        $this->assertTrue(password_verify($password, $passhash));
    }

    public function additionProviderPassword()
    {
        return [
          ['Jean2825'],
          ['T4G5h2f3R0aaaa'],
          ['Macron41Paris']
        ];
    }

    /**
     * @dataProvider additionProviderFailPassword
     */
    public function testFailSetPassword($password)
    {
        $this->expectException(InvalidArgumentException::class);

        $this->user = new User2();
        $this->user->setPassword($password);
    }

    public function additionProviderFailPassword()
    {
        return [
          ['jean2825'],
          ['123456rt'],
          ['MacronParis']
        ];
    }

/************************$birthDate**********************************/
    /**
     * @dataProvider additionProviderBirthDate
     */
    public function testSetBirthDate($birthDate)
    {
        $this->user = new User2();
        $this->user->setBirthDate($birthDate);

        $this->assertEquals($birthDate, $this->user->getBirthDate());
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

        $this->user = new User2();
        $this->user->setBirthDate($birthDate);
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
        $this->user = new User2();
        $this->user->setCreateDate($createDate);

        $this->assertEquals($createDate, $this->user->getCreateDate());
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

        $this->user = new User2();
        $this->user->setCreateDate($createDate);
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
        $this->user = new User2();
        $this->user->setUserValidation($userValidation);
        $this->assertSame($userValidation, $this->user->getUserValidation());
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
        $this->user = new User2();
        $this->user->setUserValidationDate($userValidationDate);

        $this->assertEquals($userValidationDate, $this->user->getUserValidationDate());
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

        $this->user = new User2();
        $this->user->setUserValidationDate($userValidationDate);
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
        $this->user = new User2();
        $this->user->setUserSuspended($userSuspended);
        $this->assertSame($userSuspended, $this->user->getUserSuspended());
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
        $this->user = new User2();
        $this->user->setUserSuspendedDate($userSuspendedDate);

        $this->assertEquals($userSuspendedDate, $this->user->getUserSuspendedDate());
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

        $this->user = new User2();
        $this->user->setUserSuspendedDate($userSuspendedDate);
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
        $this->user = new User2();
        $this->user->setUserDeleted($userDeleted);
        $this->assertSame($userDeleted, $this->user->getUserDeleted());
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
        $this->user = new User2();
        $this->user->setUserDeletedDate($userDeletedDate);

        $this->assertEquals($userDeletedDate, $this->user->getUserDeletedDate());
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

        $this->user = new User2();
        $this->user->setUserDeletedDate($userDeletedDate);
    }

    public function additionProviderFailUserDeletedDate()
    {
        return [
          [DateTime::createFromFormat('Y-m-d', '2022-11-12')],
          [(new DateTime())->add(new DateInterval('P1D'))]
        ];
    }
}
