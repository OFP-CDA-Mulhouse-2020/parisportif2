<?php

namespace App\Tests;

use App\Entity\User1;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class User1Test extends TestCase
{

    private $user;

    public function testUserClassStructure()
    {
        $this->user = new User1();
        $this->assertInstanceOf(User1::class, $this->user);
        $this->assertClassHasAttribute('id', User1::class);
        $this->assertClassHasAttribute('name', User1::class);
        $this->assertClassHasAttribute('firstName', User1::class);
        $this->assertClassHasAttribute('email', User1::class);
        $this->assertClassHasAttribute('password', User1::class);
        $this->assertClassHasAttribute('birthDate', User1::class);
    }



    //;*********************************** Test de l'id

    /**
     * @dataProvider additionProviderId
     */
    public function testSetId($id)
    {
        $this->user = new User1();
        $this->user->setId($id);
        $this->assertIsInt($this->user->getId());
    }

    public function additionProviderId()
    {
        return [
            [1],
            [24]
        ];
    }



    //;*********************************** Test du Nom

    /**
     * @dataProvider additionName
     */

    public function testSetName($name)
    {

        $this->user = new User1();
        $this->user->setName($name);
        $this->assertSame($name, $this->user->getName());
    }

    public function additionName()
    {
        return [
            ['Doe'],
            ['DoeDoe']
        ];
    }


    //! Test du Nom

    /**
     * @dataProvider additionNameException
     */

    public function testSetNameException($name)
    {
        $this->expectException(InvalidArgumentException::class);

        $this->user = new User1();
        $this->user->setName($name);
    }

    public function additionNameException()
    {
        return [
            [1234],
            ['DoeDoe66']
        ];
    }


    //!


    //;*********************************** firstname Prénom

    /**
     * @dataProvider additionFirstName
     */

    public function testSetFirstName($userFirstName)
    {

        $this->user = new User1();
        $this->user->setFirstName($userFirstName);
        $this->assertSame($userFirstName, $this->user->getFirstName());
    }

    public function additionFirstName()
    {
        return [
            ['Theo'],
            ['aurelie']
        ];
    }



    //! Test du Prénom

    /**
     * @dataProvider additionFirstNameException
     */

    public function testSetFirstNameException($firstName)
    {
        $this->expectException(InvalidArgumentException::class);

        $this->user = new User1();
        $this->user->setFirstName($firstName);
    }

    public function additionFirstNameException()
    {
        return [
            [1234],
            ['DoeDoe66']
        ];
    }


    //
    //
    //;***********************************Test de l'email

    /**
     * @dataProvider additionEmail
     */

    public function testSetEmail($userEmail)
    {

        $this->user = new User1();
        $this->user->setEmail($userEmail);
        $this->assertSame($userEmail, $this->user->getEmail());
    }

    public function additionEmail()
    {
        return [
            ['theo@laposte.net'],
            ['aurelie@gmail.com']
        ];
    }


    //! error Exception for email

    /**
     * @dataProvider additionEmailException
     */

    public function testSetEmailException($userEmail)
    {
        $this->expectException(InvalidArgumentException::class);

        $this->user = new User1();
        $this->user->setEmail($userEmail);
    }

    public function additionEmailException()
    {
        return [
            ['theolaposte.net'],
            ['aurelie@gmail']
        ];
    }



    //
    //
    //;*********************************** Test Password

    /**
     * @dataProvider additionPassword
     */

    public function testSetPassword($password)
    {

        $this->user = new User1();
        $this->user->setPassword($password);
        $passwordLength = strlen($this->user->getPassword());
        $this->assertGreaterThanOrEqual('8', $passwordLength);
    }

    public function additionPassword()
    {
        return [
            ['12345a67P8'],
            ['12345678P']
        ];
    }



    //;*********************************** Test Date de naissance

    //     /**
    //      * @dataProvider additionBirthDate
    //      */

    //     public function testSetBirthDate($birthDate)
    //     {

    //         // $this->user = new User1();
    //         // $this->user->setBirthDate($birthDate);
    //         // $today = date('m.d.Y');
    //         // $test = '30.01.2001';
    //         // $diff = $today->diff($test);
    //         // $this->assertGreaterThanOrEqual('18', $diff);
    //         $datetime1 = new DateTime($birthDate);
    //         $datetime1 = $this->user->getBirthDate();
    //         $datetime2 = new DateTime('2027-09-11');
    //         $interval = $datetime1->diff($datetime2);
    //         $this->assertGreaterThanOrEqual('18', $interval->format('%a'));
    //     }

    //     public function additionBirthDate()
    //     {
    //         return [
    //             ['30.11.2001']
    //         ];
    //     }
}
