<?php

namespace App\Tests\Unit;

use App\Entity\User;
use App\Service\MailerService;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerServiceTest extends TestCase
{
    public function testAssertInstanceOfMailerService(): void
    {
        $mailerStub = $this->getMockBuilder(MailerInterface::class)
                            ->getMock();
        $mailerService = new MailerService($mailerStub);
        $this->assertInstanceOf(MailerService::class, $mailerService);
    }

    public function testGenerateEmail(): void
    {
        $mailerStub = $this->getMockBuilder(MailerInterface::class)
                            ->getMock();
        $mailerService = new MailerService($mailerStub);
        $this->assertInstanceOf(MailerService::class, $mailerService);
        $user = new User();
        $user->setEmail('test@test.com');
        $email = $mailerService->generateEmail($user);
        $this->assertInstanceOf(TemplatedEmail::class, $email);
    }

    public function testSendEmail(): void
    {
        $mailerMock = $this->getMockBuilder(MailerInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['send'])
            ->getMock();
        $mailerService = new MailerService($mailerMock);
        $this->assertInstanceOf(MailerService::class, $mailerService);
        $user = new User();
        $user->setEmail('test@test.com');
        $email = $mailerService->generateEmail($user);
        $this->assertInstanceOf(TemplatedEmail::class, $email);

        $mailerMock->expects($this->once())
            ->method('send');

        $mailerService->sendEmail($email);
    }
}
