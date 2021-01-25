<?php

namespace App\Tests\Unit;

use App\Entity\CardIdFile;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class CardIdFileTest extends KernelTestCase
{
    public function testAssertCardIdFileTestInstance(): void
    {
        $cardIdFile = new CardIdFile();
        $this->assertInstanceOf(CardIdFile::class, $cardIdFile);
        $this->assertClassHasAttribute('id', CardIdFile::class);
        $this->assertClassHasAttribute('name', CardIdFile::class);
        $this->assertClassHasAttribute('valid', CardIdFile::class);
    }

    /************ Kernel ***************/


    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();
        return $kernel;
    }

    public function numberOfViolations(CardIdFile $cardIdFile, ?array $groups): int
    {
        $kernel = $this->getKernel();
        $validator = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($cardIdFile, null, $groups);
        return count($violationList);
    }


    public function testValidNameCardId(): void
    {
        $cardIdFile = new CardIdFile();
        $cardIdFile->setName('hello.jpg');
        $this->assertSame(0, $this->numberOfViolations($cardIdFile, ['cardIdFile']));
    }


    public function testInvalidNameCardId(): void
    {
        $cardIdFile = new CardIdFile();
        $cardIdFile->setName('hello');
        $this->assertSame(1, $this->numberOfViolations($cardIdFile, ['cardIdFile']));
    }
}
