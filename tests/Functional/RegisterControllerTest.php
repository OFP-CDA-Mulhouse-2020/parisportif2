<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{

    public function testLoginReponse200()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertResponseStatusCodeSame(200);
    }
/*
    public function testLoginWithAllLabel()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('form input[type=submit]');
        $this->assertCount(1, $crawler->filter('form input[name*="email"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="password"]'));
    }


    public function testLoginSubmitWithSuccess()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('form')->form();

        $form['user_login_type2[email]'] = 'daniel.cda@test.com';
        $form['user_login_type2[password]'] = 'M1cdacda8';

        $crawler = $client->submit($form);

     //   $this->assertResponseRedirects('/home/logged');
     //   $crawler = $client->followRedirect();

      //  $this->assertSelectorTextContains('h1', 'Bienvenue User !');

        $userRepository = static::$container->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('daniel.cda@test.com');

        // simulate $testUser being logged in
      //  $client->loginUser($testUser);

        // test e.g. the profile page
        $client->request('GET', '/profile');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello John!');
    }


    public function testInvalidLoginSubmit()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('form')->form();

        $crawler = $client->submit($form);

        $this->assertSelectorTextContains('', 'Email vide');
        $this->assertSelectorTextContains('', 'Password vide');
    }
*/
    public function testRegisterResponse200()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testRegisterWithAllLabel()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $this->assertSelectorExists('form');
        $this->assertCount(1, $crawler->filter('form input[name*="firstName"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="lastName"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="email"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="password"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="birthDate"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="agreeTerms"]'));
        $this->assertSelectorExists('form button[type=submit]');
    }

    public function testRegisterSubmitWithSuccess()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->filter('form')->form();

        $form['registration_form[lastName]'] = 'cda';
        $form['registration_form[firstName]'] = 'daniel';
        $form['registration_form[email]'] = 'daniel.test@phpunit.com';
        $form['registration_form[password]'] = 'M1cdacda8';
        $form['registration_form[birthDate]'] = '2000-10-02';

        $crawler = $client->submit($form);

     //   $this->assertResponseRedirects('/login/check');
     //   $crawler = $client->followRedirect();

       // $this->assertSelectorTextContains('h1', 'Le Formulaire  a été validé');
    }


    public function testInvalidRegisterSubmit()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->filter('form')->form();

        $crawler = $client->submit($form);

        $this->assertSelectorTextContains('', 'Nom vide');
        $this->assertSelectorTextContains('', 'Prénom vide');
        $this->assertSelectorTextContains('', 'Email vide');
        $this->assertSelectorTextContains('', 'Password vide');
        $this->assertSelectorTextContains('', 'Date de naissance vide');
        $this->assertSelectorTextContains('', 'Vous devez accepter les termes du contrat');
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
