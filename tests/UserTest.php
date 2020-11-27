<?php

namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    private const PATTERN_MAIL = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/';
    private const PATTERN_LOGIN = '/^[a-zA-Z0-9À-ÿ_.-]{2,16}$/';
    private const PATTERN_PASS = '/^[a-zA-Z0-9_]{8,12}$/';
    private $user;

    public function index()
    {
        $this->user = new User();
    }

    public function testAssertInstanceOfUser()
    {
        $this->index();
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertClassHasAttribute('login', User::class);
        $this->assertClassHasAttribute('password', User::class);
        $this->assertClassHasAttribute('mail', User::class);
    }

     /**
     * @dataProvider additionProviderId
     */
    public function testSetId($userId)
    {
        $this->index();
        $this->user->setId($userId);
        $this->assertIsInt($this->user->getId($userId));
    }

    public function additionProviderId()
    {
        return [
          [1],
          [23],
          [258],
          [45.5]
        ];
    }

    /**
     * @dataProvider additionProviderMail
     */
    public function testSetMail($mail)
    {
        $pattern = self::PATTERN_MAIL;

        $this->index();
        $this->user->setMail($mail);
        $this->assertMatchesRegularExpression($pattern, $this->user->getMail($mail));
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
     * @dataProvider additionProviderLogin
     */
    public function testSetLogin($login)
    {
        $pattern = self::PATTERN_LOGIN;

        $this->index();
        $this->user->setLogin($login);
        $this->assertMatchesRegularExpression($pattern, $this->user->getLogin($login));
    }


    public function additionProviderLogin()
    {
        return [
            ['fgrej-fdssd'],
            ['élaHfjfiod'],
            ['ÀteÀde8_6'],
            ['fdsff.dfzadde'],
            ['Username']
        ];
    }

    /**
     * @dataProvider additionProviderPassword
     */
    public function testSetPassword($pass)
    {
        $pattern = self::PATTERN_PASS;
        $this->index();
        $this->user->setPassword($pass);
        $this->assertMatchesRegularExpression($pattern, $this->user->getPassword($pass));
    }



    public function additionProviderPassword()
    {
        return [
            ['fsd45tu6'],
            ['HlHfjfiod'],
            ['ateade_86'],
            ['12lettresUni']
        ];
    }
}
