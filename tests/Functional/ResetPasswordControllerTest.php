<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResetPasswordControllerTest extends WebTestCase
{

    public function testSendEmailToResetPassword(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/reset-password');

        $form = $crawler->filter('form')->form();

        $form['reset_password_request_form[email]'] = 'ladji.cda@test.com';

        $client->submit($form);
        $this->assertEmailCount(1);

        $this->assertResponseRedirects('/reset-password/check-email');
    }
}
