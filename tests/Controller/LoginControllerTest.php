<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testShowPost()
    {
        $client = static::createClient();

        $client->request('GET', '/login');
        // $client->request(Request::METHOD_GET, '/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testLoginPageTextContent()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        // $this->assertCount(1, $crawler->filter('h1#titre-principal'));
        $this->assertSelectorTextContains('h1#titre-principal', 'Titre principal');
        // $this->assertGreaterThan(0, $crawler->filter('h2')->count(), 'Entrez vos paramètres de connexion :');
        $this->assertSelectorTextContains('h2', 'Entrez vos paramètres de connexion :');
    }

    public function testUserFormWithAllLabel()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertSelectorExists('form');
        $this->assertEquals(3, $crawler->filter('form input')->count());
        $this->assertSelectorExists('form button[type="submit"]');
        $this->assertCount(1, $crawler->filter('form input[type="text"][placeholder="Entrez votre identifiant"]'));
        $this->assertCount(1, $crawler->filter('form input[type="password"][placeholder="Entrez votre mot de passe"]'));
    }

    public function testSubmitForm()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('button[class="loginForm_submit"]')->form([
            'user_login[lastName]' => 'John',
            'user_login[password]' => '1epppdpdpdpE'
        ]);


        // $form = $crawler->filter('button[class="loginForm_submit"]')->form();
        // $form['user_login[name]'] = 'John';
        // $form['user_login[password]'] = '1epppdpdpdpE';

        $client->submit($form);
        // $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseRedirects('/login/check');
        $client->followRedirect();
    }
}
