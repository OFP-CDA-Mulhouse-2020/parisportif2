<?php

namespace App\Tests\Functional;

use Symfony\Component\Panther\PantherTestCase;

class PantherHomeControllerTest extends PantherTestCase
{
    public function testResponse200(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/login');
        $this->assertSelectorTextContains('h1', 'CONNEXION');

        $form = $crawler->filter('form')->form();
        $form['email'] = 'ladji.cda@test.com';
        $form['password'] = 'M1cdacda8';

        $client->submit($form);

        $crawler = $client->clickLink('Accueil');
        $this->assertSelectorTextContains('a', 'LOGO');

        $client->waitFor('#betBoard'); // wait for element to be attached to the DOM

        $this->assertSelectorWillExist('section#page-content');

        // ASIDE LEFT
        $this->assertSelectorWillExist('aside.aside-left');
        // Advertising -Insert-1
        $this->assertEquals(1, $crawler->filter('aside.aside-left div.advertising-insert-1')->count());
        // Navigation Pari
        $this->assertEquals(1, $crawler->filter('aside.aside-left div.nav-bet')->count());

        $this->assertSelectorWillExist('div.nav-bet>div.accordion');
        $this->assertEquals(4, $crawler->filter('div.nav-bet button.accordion-button')->count());


        // SECTION CENTRALE
        $this->assertSelectorWillExist('section.central');
        //CENTRAL SECTION CONTENT
        // Carousel
        $this->assertEquals(1, $crawler->filter('div#carouselExampleIndicators')->count());
        $this->assertGreaterThanOrEqual(1, $crawler->filter('img')->count());
        $this->assertEquals(1, $crawler->filter('a[class="carousel-control-prev"]')->count());
        $this->assertEquals(1, $crawler->filter('a[class="carousel-control-next"]')->count());

        //Bet Board
        $this->assertEquals(1, $crawler->filter('section.central div.bet-board')->count());
        $this->assertSelectorWillContain('#betBoard h4', 'Top du moment');
        $this->assertSelectorWillContain('#betBoard  table', 'Match');

        // ASIDE RIGHT
        $this->assertSelectorWillExist('aside.aside-right');

        //ASIDE RIGHT CONTENT
        $this->assertSelectorWillExist('section.bet-ticket');
        $this->assertSelectorWillExist('section.bet-search');
        $this->assertSelectorWillExist('section.advertising-insert-2');


        //Ticket section
        $this->assertSelectorWillContain('section.bet-ticket h6', 'Ticket de paris');
        $this->assertSelectorWillContain('p', 'Ajouter des paris');
        $this->assertSelectorWillContain('.bet-ticket button', 'Pariez !');

        //Search section
        $this->assertSelectorWillContain('section.bet-search h6', 'Chercher un pari');
        $this->assertSelectorWillExist('section.bet-search input[type="text"]');
        $this->assertSelectorWillExist('section.bet-search button[type="submit"]');
    }

/*
    public function testContentSectionStructure(): void
    {
       Ancien tests
        sleep(3);
        $client->waitForElementToContain('#page-content', 'Top du moment');
        // wait for text to be inserted in the element content

      //  $crawler->selectButton('Football');
        $client->waitForElementToContain('#betBoard', 'Match');


        $crawler = $client->clickLink('Mon Portefeuille');
        sleep(1);
        $client->followRedirects();
       // $crawler = $client->clickLink('Solde du compte');

     //   $this->assertSelectorTextContains('h3', 'Solde du compte');

        sleep(1);
        // return to Home
        /*$crawler = $client->clickLink('Accueil');
        $client->waitForElementToContain('#betBoard', 'Match');
        sleep(3);
    }
*/
}
