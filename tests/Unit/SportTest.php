<?php

namespace App\Tests;

use App\Entity\Sport;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SportTest extends KernelTestCase
{
    public function testInstanceOfSport()
    {
        $sport = new Sport();
        $this->assertInstanceOf(Sport::class, $sport);
    }
}
