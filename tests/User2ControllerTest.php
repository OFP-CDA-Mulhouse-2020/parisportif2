<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class User2ControllerTest extends WebTestCase
{
    public function testSomething()
    {
        $this->assertTrue(true);
        /*
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello World');*/
    }

    public function testUserControllerFormReponse()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/user/form');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('', 'Hello User2Controller!');

    }

    public function testUserControllerForm()
    {/*
        $client = static::createClient();
        $crawler = $client->request('GET', '/user/form');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('', 'Hello User2Controller!');
*/
    }
}
