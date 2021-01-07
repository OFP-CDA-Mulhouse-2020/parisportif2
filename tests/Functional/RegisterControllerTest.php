<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{
    public function testRegisterResponse200(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testRegisterWithAllLabel(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $this->assertSelectorExists('form');
        $this->assertCount(1, $crawler->filter('form input[name*="firstName"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="lastName"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="email"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="plainPassword"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="birthDate"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="agreeTerms"]'));
        $this->assertSelectorExists('form button[type=submit]');
    }

    public function testRegisterSubmitWithSuccess(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->filter('form')->form();

        $form['registration[lastName]'] = 'cda';
        $form['registration[firstName]'] = 'daniel';
        $form['registration[email]'] = 'daniel.cda@phpunit15.com';
        $form['registration[plainPassword]'] = 'M1cdacda8';
        $form['registration[birthDate]'] = '2000-10-02';
        $form['registration[agreeTerms]'] = "1";

        $crawler = $client->submit($form);

        $this->assertResponseRedirects('/app');
        $crawler = $client->followRedirect();

        $this->assertSelectorTextContains('', 'Le Formulaire a été validé');
    }

    public function testInvalidRegisterSubmit(): void
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

    public function testInvalidRegisterSubmitwithEmailAlreadyUse(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->filter('form')->form();
        $form['registration[lastName]'] = 'cda';
        $form['registration[firstName]'] = 'daniel';
        $form['registration[email]'] = 'daniel.cda@test.com';
        $form['registration[plainPassword]'] = 'M1cdacda8';
        $form['registration[birthDate]'] = '2000-10-02';
        $form['registration[agreeTerms]'] = "1";

        $crawler = $client->submit($form);

        $this->assertSelectorTextContains('', 'Il y a déjà un compte avec cet email');
    }


    public function testUserSetOnDb(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        $user = $userRepository->findOneBy(['email' => 'daniel.cda@test.com']);

        $this->assertInstanceOf(User::class, $user);
    }
}
