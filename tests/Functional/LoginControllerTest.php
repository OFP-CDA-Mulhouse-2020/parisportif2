<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testShowPost(): void
    {
        $client = static::createClient();

        $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testLoginPageTextContent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        // $this->assertCount(1, $crawler->filter('h1#titre-principal'));
        $this->assertSelectorTextContains('h1#titre-principal', 'CONNEXION');
        // $this->assertGreaterThan(0, $crawler->filter('h2')->count(), 'Entrez vos paramètres de connexion :');
//        $this->assertSelectorTextContains('h2', 'Entrez vos paramètres de connexion :');
    }


    public function testUserFormWithAllLabel(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertSelectorExists('form');
        $this->assertEquals(4, $crawler->filter('form input')->count());
        $this->assertSelectorExists('form button[type="submit"]');
        $this->assertCount(1, $crawler->filter('form input[type="email"][placeholder="Entrez votre email"]'));
        $this->assertCount(
            1,
            $crawler->filter('form input[type="password"][placeholder="Entrez votre mot de passe"]')
        );
    }

    public function testSubmitFormWithSuccess(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('form')->form();
        $form['email'] = 'ladji.cda@test.com';
        $form['password'] = 'M1cdacda8';

        $client->submit($form);

        $this->assertResponseRedirects('/app');
        $client->followRedirect();

        $this->assertSelectorTextContains('', 'Accueil');
    }



    public function testInvalidConnexionSubmitWithIncorrectEmail(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('form')->form();
        $form['email'] = 'ladji.cda@test2.com';
        $form['password'] = 'M1cdacda8';

        $client->submit($form);

        $client->followRedirect();

        $this->assertSelectorTextContains('', 'Email could not be found.');
    }

    public function testInvalidConnexionSubmitWithIncorrectPassword(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('form')->form();
        $form['email'] = 'ladji.cda@test.com';
        $form['password'] = 'M1cdacda88';

        $client->submit($form);
        $client->followRedirect();

        $this->assertSelectorTextContains('', 'Invalid credentials.');
    }
}
