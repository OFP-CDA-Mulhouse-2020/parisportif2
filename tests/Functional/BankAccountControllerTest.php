<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BankAccountControllerTest extends WebTestCase
{
    public function testGetBankAccountInformationsResponse200(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/wallet/bank-account');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetBankAccountInformationsWithAllLabel(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/wallet/bank-account');

        $this->assertSelectorTextContains('div.main h3', 'Modifier les coordonnées bancaires');
        $this->assertCount(1, $crawler->filter('form input[name*="ibanCode"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="bicCode"]'));
    }

    public function testSetBankAccountInformationsSuccess(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/wallet/bank-account');
        $link = $crawler
            ->filter('div.main a')
            ->eq(0)
            ->link()
        ;

        $crawler = $client->click($link);
        $this->assertCount(1, $crawler->filter('form input[name*="ibanCode"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="bicCode"]'));
        $this->assertSelectorExists('form button[type="submit"]');

        $form = $crawler
            ->filter('form')
            ->eq(0)
            ->form();

        $form['bank_account[ibanCode]'] = 'FR7630006000011234567890189';
        $form['bank_account[bicCode]'] = 'BNPAFRPPTAS';

        $client->submit($form);
        $this->assertResponseRedirects('/app/wallet/bank-account');
    }

    public function testSetBankAccountInformationsFail(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/wallet/bank-account');
        $link = $crawler
            ->filter('div.main a')
            ->eq(0)
            ->link()
        ;

        $crawler = $client->click($link);
        $this->assertCount(1, $crawler->filter('form input[name*="ibanCode"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="bicCode"]'));
        $this->assertSelectorExists('form button[type="submit"]');

        $form = $crawler
            ->filter('form')
            ->eq(0)
            ->form();

        $form['bank_account[ibanCode]'] = 'FR763000600001123456789';
        $form['bank_account[bicCode]'] = 'BNPAFRP';

        $client->submit($form);

        $this->assertSelectorTextContains('', 'Cet IBAN n\'est pas valide.');
        $this->assertSelectorTextContains('', 'Ce code BIC n\'est pas valide.');
    }
}