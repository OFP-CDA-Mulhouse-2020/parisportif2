<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserProfileControllerTest extends WebTestCase
{
    public function testUserProfileResponse200(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('form')->form();
        $form['email'] = 'ladji.cda@test.com';
        $form['password'] = 'M1cdacda8';

        $crawler = $client->submit($form);

        $this->assertResponseRedirects('/app');
        $crawler = $client->followRedirect();

        $crawler = $client->request('GET', '/app/user/profile');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testUserProfileWithAllLabel(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('form')->form();
        $form['email'] = 'ladji.cda@test.com';
        $form['password'] = 'M1cdacda8';

        $crawler = $client->submit($form);

        $this->assertResponseRedirects('/app');
        $crawler = $client->followRedirect();

        $crawler = $client->request('GET', '/app/user/profile');

        $this->assertSelectorTextContains('ul li a', 'Mon profil');
        $this->assertSelectorTextContains('', 'Mes informations');
        $this->assertSelectorTextContains('', 'Activation/Désactivation de compte');
        $this->assertSelectorTextContains('', 'Exclusion/Clôture du compte');

        $this->assertSelectorTextContains('', 'Mon portefeuille');
        $this->assertSelectorTextContains('li', 'Aide');
        $this->assertSelectorTextContains('li', 'Nous contacter');
    }
}
