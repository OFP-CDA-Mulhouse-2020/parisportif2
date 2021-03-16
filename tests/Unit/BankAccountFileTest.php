<?php

namespace App\Tests\Unit;

use App\Entity\BankAccountFile;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BankAccountFileTest extends KernelTestCase
{
    public function testAssertCardIdFileTestInstance(): void
    {
        $bankAccountFile = new BankAccountFile();
        $this->assertInstanceOf(BankAccountFile::class, $bankAccountFile);
        $this->assertClassHasAttribute('id', BankAccountFile::class);
        $this->assertClassHasAttribute('name', BankAccountFile::class);
        $this->assertClassHasAttribute('valid', BankAccountFile::class);
    }

    /************ Kernel ***************/


    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();
        return $kernel;
    }

    public function numberOfViolations(BankAccountFile $bankAccountFile, ?array $groups): int
    {
        $kernel = $this->getKernel();
        $validator = $kernel->getContainer()->get('validator');
        assert($validator instanceof ValidatorInterface);
        $violationList = $validator->validate($bankAccountFile, null, $groups);
        return count($violationList);
    }


    public function testValidNameCardId(): void
    {
        $bankAccountFile = new BankAccountFile();
        $bankAccountFile->setName('hello.jpg');
        $this->assertSame(0, $this->numberOfViolations($bankAccountFile, ['bankAccountFile']));
    }


    public function testInvalidNameCardId(): void
    {
        $bankAccountFile = new BankAccountFile();
        $bankAccountFile->setName('hello');
        $this->assertSame(1, $this->numberOfViolations($bankAccountFile, ['bankAccountFile']));
    }
}
