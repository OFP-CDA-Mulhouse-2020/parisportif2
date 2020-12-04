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

        $this->assertResponseRedirects('/home/logged');
        $crawler = $client->followRedirect();

        $this->assertSelectorTextContains('h1', 'Bienvenue User !');
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


    /*
    public function testUserConnexion()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/user/form');
        $this->assertSelectorTextContains('div', 'Connexion');

        $buttonCrawlerNode = $crawler->filter('input[type=submit]');
        $form = $buttonCrawlerNode->form();

        $form['form[connexion][mail]'] = 'daniel.cda@test.com';
        $form['form[connexion][password]'] =  'M1cdacda8';

        $client->submit($form);



    }
*/
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
