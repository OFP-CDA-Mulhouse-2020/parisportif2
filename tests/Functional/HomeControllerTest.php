<?php

// login: ladji.cda@test.com
//// mdp : M1cdacda8
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{


    public function testIfPageExist(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('form')->form();
        $form['email'] = 'ladji.cda@test.com';
        $form['password'] = 'M1cdacda8';


        $this->assertResponseRedirects('/app');
    }

    // HEADER
    public function testHeaderStructure(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('form')->form();
        $form['email'] = 'ladji.cda@test.com';
        $form['password'] = 'M1cdacda8';

        $crawler = $client->followRedirect();

        $this->assertSelectorExists('header');

        // LOGO
        $this->assertSelectorExists('#logo');
        $this->assertEquals(1, $crawler->filter('div#logo a[class~="navbar-brand"]')->count());

        // MAIN NAV
        $this->assertSelectorExists('nav.nav-main');
        $this->assertEquals(4, $crawler->filter('nav.nav-main li.nav-item>a[class~="nav-link"]')->count());

        // SECONDARY NAV
        $this->assertSelectorExists('nav.nav-secondary');
        $this->assertEquals(10, $crawler->filter('nav.nav-secondary li.nav-item>a[class~="nav-link"]')->count());
    }


    // SECTION#CONTENT
    public function testContentSectionStructure(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('form')->form();
        $form['email'] = 'ladji.cda@test.com';
        $form['password'] = 'M1cdacda8';

        $crawler = $client->followRedirect();

        $this->assertSelectorExists('section#content');

        // ASIDE LEFT
        $this->assertSelectorExists('aside.aside-left');
        // Encart promotionnel
        $this->assertEquals(1, $crawler->filter('aside.aside-left div.advertising-insert-1')->count());
        // Navigation Pari
        $this->assertEquals(1, $crawler->filter('aside.aside-left div.nav-bet')->count());

        // SECTION CENTRAL
        $this->assertSelectorExists('section.central');

        $this->assertEquals(1, $crawler->filter('section.central div.carousel')->count());
        $this->assertEquals(1, $crawler->filter('div#carouselExampleIndicators')->count());

        //Tableau prise de paris
        $this->assertEquals(1, $crawler->filter('section.central div.bet-board')->count());
    }
}
