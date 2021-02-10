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
    }
}
