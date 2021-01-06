<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserProfileControllerTest extends WebTestCase
{
    public function testUserProfileResponse200(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testUserProfileWithAllLabel(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile');
        $this->assertResponseStatusCodeSame(200);

        $this->assertEquals(1, $crawler->filter('div.left-side-menu')->count());
        $this->assertEquals(13, $crawler->filter('div.left-side-menu li')->count());

        $this->assertSelectorTextContains('ul li a', 'Mon profil');
        $this->assertSelectorTextContains('', 'Mon portefeuille');
        $this->assertSelectorTextContains('', 'Aide');
        $this->assertSelectorTextContains('', 'Nous contacter');

        $this->assertEquals(1, $crawler->filter('div.right-side-menu')->count());
        $this->assertEquals(2, $crawler->filter('div.right-side-menu li')->count());

        $this->assertEquals(1, $crawler->filter('div.main')->count());
    }

    public function testUserProfileConnexion(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/connexion');
        $this->assertResponseStatusCodeSame(200);

        $this->assertSelectorTextContains('div.main h3', 'Connexion');
        $this->assertCount(1, $crawler->filter('form input[name*="email"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="password"]'));
    }

    public function testUserProfileEditMail(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/connexion');
        $this->assertResponseStatusCodeSame(200);

        $link = $crawler
            ->filter('div.main a')
            ->eq(0)
            ->link()
        ;

        $crawler = $client->click($link);

        $this->assertSelectorTextContains('div.main h3', 'Connexion');
        $this->assertCount(1, $crawler->filter('form input[name*="oldEmail"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="newEmail"]'));
        $this->assertSelectorExists('form button[type="submit"]');
    }

    public function testUserProfileEditPassword(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/connexion');
        $this->assertResponseStatusCodeSame(200);

        $link = $crawler
            ->filter('div.main a')
            ->eq(1)
            ->link()
        ;

        $crawler = $client->click($link);

        $this->assertSelectorTextContains('div.main h3', 'Connexion');
        $this->assertCount(1, $crawler->filter('form input[name*="oldPassword"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="newPassword"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="confirmPassword"]'));

        $this->assertSelectorExists('form button[type="submit"]');
    }

    public function testUserProfileIdentity(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/information');
        $this->assertResponseStatusCodeSame(200);

        $this->assertSelectorTextContains('div.main h3', 'Mes informations');
        $this->assertCount(1, $crawler->filter('form input[name*="lastName"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="firstName"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="birthDate"]'));

        $this->assertCount(1, $crawler->filter('form input[name*="addressNumberAndStreet"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="zipCode"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="city"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="country"]'));
    }

    public function testUserProfileActivation(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/activation');
        $this->assertResponseStatusCodeSame(200);

        $this->assertSelectorTextContains('div.main h4', 'Activation du compte');
        $this->assertSelectorTextContains('div.main h4:nth-of-type(2)', 'Désactivation du compte');
        $this->assertSelectorTextContains('div.main a', 'Désactiver mon compte');
    }

    public function testUserProfileSuspend(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/suspend');
        $this->assertResponseStatusCodeSame(200);

        $this->assertSelectorTextContains('div.main h4', 'Description');
        $this->assertSelectorTextContains('div.main h4:nth-of-type(2)', 'Quel type d\'auto exclusion souhaitez vous ?');
        $this->assertCount(1, $crawler->filter('form select[name*="suspendType"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="suspendAt"]'));
        $this->assertSelectorExists('form button[type="submit"]');
    }
}
