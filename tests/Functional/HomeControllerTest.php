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


    // SECTION#PAGE-CONTENT
  /*  public function testContentSectionStructure(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $client->request('GET', '/app');

        $this->assertSelectorExists('section#page-content');

        // ASIDE LEFT
        $this->assertSelectorExists('aside.aside-left');

        // SECTION CENTRALE
        $this->assertSelectorExists('section.central');

        // ASIDE RIGHT
        $this->assertSelectorExists('aside.aside-right');
    }


    //ASIDE LEFT CONTENT
    public function testAsideLeftContent(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app');

        // Advertising -Insert-1
        $this->assertEquals(1, $crawler->filter('aside.aside-left div.advertising-insert-1')->count());
        // Navigation Pari
        $this->assertEquals(1, $crawler->filter('aside.aside-left div.nav-bet')->count());
    }

    public function testNavBetContent(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app');

        $this->assertSelectorExists('div.nav-bet>div.accordion');
        $this->assertEquals(4, $crawler->filter('div.nav-bet button.accordion-button')->count());
    }


    //CENTRAL SECTION CONTENT
    public function testSectionCentralContent(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app');

        // Carousel
        $this->assertEquals(1, $crawler->filter('div#carouselExampleIndicators')->count());

        //Bet Board
        $this->assertEquals(1, $crawler->filter('section.central div.bet-board')->count());
    }

    public function testCarouselContent(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app');

        // Carousel
        $this->assertGreaterThanOrEqual(1, $crawler->filter('img')->count());
        $this->assertEquals(1, $crawler->filter('a[class="carousel-control-prev"]')->count());
        $this->assertEquals(1, $crawler->filter('a[class="carousel-control-next"]')->count());
    }


    //ASIDE RIGHT CONTENT
    public function testAsideRightContent(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app');

        $this->assertEquals(3, $crawler->filter('aside.aside-right section')->count());


        $this->assertSelectorExists('section.bet-ticket');
        $this->assertSelectorExists('section.bet-search');
        $this->assertSelectorExists('section.advertising-insert-2');
    }

    //Ticket section
    public function testBetTicketSection(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $client->request('GET', '/app');

        $this->assertSelectorTextContains('', 'Ticket de paris');
        $this->assertSelectorTextContains('', 'Ajouter des paris');
        $this->assertSelectorTextContains('.bet-ticket button', 'Pariez !');
    }

    //Search section
    public function testBetSearch(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $client->request('GET', '/app');

        $this->assertSelectorTextContains('', 'Chercher un pari');
        $this->assertSelectorExists('section.bet-search input[type="text"]');
        $this->assertSelectorExists('section.bet-search button[type="submit"]');
    }*/

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
