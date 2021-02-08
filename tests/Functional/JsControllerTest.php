<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Panther\PantherTestCase;

class JsControllerTest extends PantherTestCase
{
    public function testResponse200(): void
    {
        $client = static::createPantherClient();
        $client->request('GET', '/login');

        $this->assertSelectorTextContains('', 'Titre principal');
    }
}
