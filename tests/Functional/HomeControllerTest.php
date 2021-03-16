<?php

// login: ladji.cda@test.com
// mdp : M1cdacda8
namespace App\Tests\Functional;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{

    public function testIfPageExist(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $client->request('GET', '/app');

        $this->assertResponseStatusCodeSame(200);
    }

    // HEADER
    public function testHeaderStructure(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app');

        $this->assertSelectorExists('header');
        $this->assertEquals(2, $crawler->filter('header li.dropdown')->count());

        // LOGO
        $this->assertSelectorExists('#logo');
        $this->assertEquals(1, $crawler->filter('div#logo a[class~="navbar-brand"]')->count());

        // MAIN NAV
        $this->assertSelectorExists('nav.nav-main');
        $this->assertEquals(5, $crawler->filter('nav.nav-main li.nav-item>a[class~="nav-link"]')->count());

        // SECONDARY NAV
        $this->assertSelectorExists('nav.nav-secondary');
        $this->assertEquals(10, $crawler->filter('nav.nav-secondary li.nav-item>a[class~="nav-link"]')->count());
    }

    public function testHeaderNavDropdownContent(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app');

        // dropdown 1
        $this->assertEquals(5, $crawler->filter('.dropdown-1 a.dropdown-item')->count());

        // dropdown 2
        $this->assertEquals(5, $crawler->filter('.dropdown-2 a.dropdown-item')->count());
    }

    //FOOTER
    public function testFooterStructure(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $client->request('GET', '/app');

        $this->assertSelectorExists('footer');
        $this->assertSelectorExists('.footer-content');
        $this->assertSelectorExists('.footer-disclaimer');
        $this->assertSelectorExists('.footer-copyright');
    }
}
