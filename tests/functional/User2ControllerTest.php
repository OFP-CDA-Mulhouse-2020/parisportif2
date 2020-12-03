<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class User2ControllerTest extends WebTestCase
{

    public function testUserFormReponse200()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/user/form');
        //    $this->assertResponseIsSuccessful();
        //    $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseStatusCodeSame(200);
    }

    public function testUserFormWithAllLabel()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/user/form');
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('form input[type=submit]');
        $this->assertCount(1, $crawler->filter('form input[name*="email"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="password"]'));
    }


    public function testFormSubmitWithSuccess()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/user/form');

        $form = $crawler->filter('form')->form();

        $form['user_login[email]'] = 'daniel.cda@test.com';
        $form['user_login[password]'] = 'M1cdacda8';

        $crawler = $client->submit($form);

        $this->assertResponseRedirects('/user/logged');
        $crawler = $client->followRedirect();

        $this->assertSelectorTextContains('h1', 'Bienvenue User !');
    }


    public function testInvalidFormSubmit()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/user/form');

        $form = $crawler->filter('form')->form();

        $crawler = $client->submit($form);

        $this->assertSelectorTextContains('', 'password incorrect');
        $this->assertSelectorTextContains('', 'email incorrect');
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
