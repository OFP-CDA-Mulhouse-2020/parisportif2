<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class User2ControllerTest extends WebTestCase
{

    public function testUserConnexionReponse200()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/connexion');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testUserConnexionWithAllLabel()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/connexion');
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('form input[type=submit]');
        $this->assertCount(1, $crawler->filter('form input[name*="email"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="password"]'));
    }


    public function testConnexionSubmitWithSuccess()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/connexion');

        $form = $crawler->filter('form')->form();

        $form['user_login_type2[email]'] = 'daniel.cda@test.com';
        $form['user_login_type2[password]'] = 'M1cdacda8';

        $crawler = $client->submit($form);

     //   $this->assertResponseRedirects('/home/logged');
     //   $crawler = $client->followRedirect();

      //  $this->assertSelectorTextContains('h1', 'Bienvenue User !');
    }


    public function testInvalidConnexionSubmit()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/connexion');

        $form = $crawler->filter('form')->form();

        $crawler = $client->submit($form);

        $this->assertSelectorTextContains('', 'Email vide');
        $this->assertSelectorTextContains('', 'Password vide');
    }

    public function testUserSubscribeResponse200()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/subscribe');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testUserSubscribeWithAllLabel()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/subscribe');
        $this->assertSelectorExists('form');
        $this->assertCount(1, $crawler->filter('form input[name*="firstName"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="lastName"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="email"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="password"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="birthDate"]'));
        $this->assertSelectorExists('form button[type=submit]');
    }


    public function testSubscribeSubmitWithSuccess()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/subscribe');

        $form = $crawler->filter('form')->form();

        $form['user_subscribe[lastName]'] = 'cda';
        $form['user_subscribe[firstName]'] = 'daniel';
        $form['user_subscribe[email]'] = 'daniel.test@phpunit.com';
        $form['user_subscribe[password]'] = 'M1cdacda8';
        $form['user_subscribe[birthDate]'] = '2000-10-02';

        $crawler = $client->submit($form);

    //    $this->assertResponseRedirects('/home/logged');
    //    $crawler = $client->followRedirect();

     //   $this->assertSelectorTextContains('h1', 'Bienvenue User !');
    }


    public function testInvalidSubscribeSubmit()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/subscribe');

        $form = $crawler->filter('form')->form();

        $crawler = $client->submit($form);

        $this->assertSelectorTextContains('', 'Nom vide');
        $this->assertSelectorTextContains('', 'Prénom vide');
        $this->assertSelectorTextContains('', 'Email vide');
        $this->assertSelectorTextContains('', 'Password vide');
        $this->assertSelectorTextContains('', 'Date de naissance vide');
    }

/*
    public function testDb()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
      //  var_dump($userRepository);

        // retrieve the test user
        $testUser = $userRepository->findBy(['email'=>'daniel.cda@test.com']);

        var_dump($testUser);
    }
    */
}
