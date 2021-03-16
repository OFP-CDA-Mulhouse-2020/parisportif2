<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserProfileControllerTest extends WebTestCase
{
    public function testUserProfileResponse200(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $client->request('GET', '/app/profile');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testUserProfileWithAllLabel(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/profile');
        $this->assertResponseStatusCodeSame(200);

        $this->assertEquals(1, $crawler->filter('div.nav-user-left')->count());
        $this->assertEquals(12, $crawler->filter('div.nav-user-left a')->count());

        $this->assertSelectorTextContains('', 'Mon profil');
        $this->assertSelectorTextContains('', 'Mon portefeuille');
        $this->assertSelectorTextContains('', 'Aide');
        $this->assertSelectorTextContains('', 'Nous contacter');

        $this->assertEquals(1, $crawler->filter('div.nav-user-right')->count());
        $this->assertEquals(2, $crawler->filter('div.nav-user-right a')->count());

        $this->assertSelectorTextContains('', 'Mes tickets');
        $this->assertSelectorTextContains('', 'Mes favoris');

        $this->assertEquals(1, $crawler->filter('div.main')->count());
    }


//    public function testUserProfileIdentity(): void
//    {
//        $client = static::createClient();
//        $userRepository = static::$container->get(UserRepository::class);
//        assert($userRepository instanceof UserRepository);
//        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
//        assert($testUser instanceof User);
//        $client->loginUser($testUser);
//
//        $crawler = $client->request('GET', '/app/profile/information');
//        $this->assertResponseStatusCodeSame(200);
//
//        $this->assertSelectorTextContains('div.main h3', 'Mes informations');
//        $this->assertCount(1, $crawler->filter('form input[name*="lastName"]'));
//        $this->assertCount(1, $crawler->filter('form input[name*="firstName"]'));
//        $this->assertCount(1, $crawler->filter('form input[name*="birthDate"]'));
//
//        $this->assertCount(1, $crawler->filter('form input[name*="addressNumberAndStreet"]'));
//        $this->assertCount(1, $crawler->filter('form input[name*="zipCode"]'));
//        $this->assertCount(1, $crawler->filter('form input[name*="city"]'));
//        $this->assertCount(1, $crawler->filter('form input[name*="country"]'));
//    }

    public function testUserProfileActivation(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $client->request('GET', '/app/profile/activation');
        $this->assertResponseStatusCodeSame(200);

        $this->assertSelectorTextContains('div.main h4', 'Activation du compte');
        $this->assertSelectorTextContains('div.main h4:nth-of-type(2)', 'Fermeture du compte');
        $this->assertSelectorTextContains('div.main button', 'Fermer mon compte');
    }

    public function testUserProfileSuspend(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/profile/suspend');
        $this->assertResponseStatusCodeSame(200);

        $this->assertSelectorTextContains('div.main h4', 'Description');
        $this->assertSelectorTextContains('div.main h4:nth-of-type(2)', 'Quel type d\'auto exclusion souhaitez vous ?');
        $this->assertCount(1, $crawler->filter('form select[name*="suspendType"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="suspendUntil"]'));
        $this->assertSelectorExists('form button[type="submit"]');
    }
}
