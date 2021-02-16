<?php

namespace App\Tests\Functional;

use Symfony\Component\Panther\PantherTestCase;

class JsControllerTest extends PantherTestCase
{
    public function testResponse200(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/login');
        $this->assertSelectorTextContains('h1', 'Titre principal');

        $form = $crawler->filter('form')->form();
        $form['email'] = 'ladji.cda@test.com';
        $form['password'] = 'M1cdacda8';

        $client->submit($form);
        $this->assertSelectorTextContains('a', 'LOGO');

        sleep(3);
        $client->waitForElementToContain('#page-content', 'Top du moment');
        // wait for text to be inserted in the element content

      //  $crawler->selectButton('Football');
        $client->waitForElementToContain('#betBoard', 'Match');



        $crawler = $client->clickLink('Mon Portefeuille');
        sleep(1);
        $client->followRedirects();
        $crawler = $client->clickLink('Solde du compte');

        $this->assertSelectorTextContains('h3', 'Solde du compte');

        sleep(1);


        // return to Home
        /*$crawler = $client->clickLink('Accueil');
        $client->waitForElementToContain('#betBoard', 'Match');
        sleep(3);*/
    }
}
