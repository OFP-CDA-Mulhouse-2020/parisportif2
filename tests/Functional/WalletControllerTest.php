<?php

namespace App\Tests\Functional;

use App\Entity\Payment;
use App\Repository\PaymentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WalletControllerTest extends WebTestCase
{
    public function testGetWalletBalanceResponse200(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/wallet/balance');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetWalletBalanceWithAllLabel(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/wallet/balance');

        $this->assertSelectorTextContains('div.main h3', 'Solde du compte');
        $this->assertCount(1, $crawler->filter('form input[name*="startAt"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="endAt"]'));
        $this->assertSelectorExists('form button[type="submit"]');
    }

    public function testAddMoneyToWalletResponse200(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/wallet/add-money');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testAddMoneyToWalletWithAllLabel(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/wallet/add-money');

        $this->assertSelectorTextContains('div.main h3', 'Ajouter des fonds à mon portefeuille');
        $this->assertCount(3, $crawler->filter('form input[name*="meansOfPayment"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="amount"]'));
        $this->assertSelectorExists('form button[type="submit"]');
    }

    public function testAddMoneyToWalletSuccess(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/wallet/add-money');
        $form = $crawler
            ->filter('form')
            ->eq(0)
            ->form();

        $form['add_money[amount]'] = '30';

        $client->submit($form);

        $this->assertSelectorTextContains('', 'Votre versement a été réalisé avec succès !');
    }

    public function testWithdrawMoneyFromWalletResponse200(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/wallet/withdraw-money');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testWithdrawMoneyFromWalletWithAllLabel(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/wallet/withdraw-money');

        $this->assertSelectorTextContains('div.main h3', 'Faire un retrait');
        $this->assertCount(1, $crawler->filter('form input[name*="amount"]'));
        $this->assertSelectorExists('form button[type="submit"]');
    }

    public function testWithdrawMoneyFromWalletSuccess(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/wallet/withdraw-money');
        $form = $crawler
            ->filter('form')
            ->eq(0)
            ->form();

        $form['withdraw_money[amount]'] = '35';

        $client->submit($form);

        $this->assertSelectorTextContains('', 'Votre versement a été réalisé avec succès !');
    }

    public function testPaymentSetOnDb(): void
    {
        static::createClient();
        $paymentRepository = static::$container->get(PaymentRepository::class);

        $payment = $paymentRepository->findOneBy(['sum' => 3500]);

        $this->assertInstanceOf(Payment::class, $payment);
    }

    public function testWithdrawMoneyFromWalletFail(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/wallet/withdraw-money');
        $form = $crawler
            ->filter('form')
            ->eq(0)
            ->form();

        $form['withdraw_money[amount]'] = '100';

        $client->submit($form);

        $this->assertSelectorTextContains('', 'Montant supérieur au solde disponible');
    }

    public function testGetLimitAmountPerWeekFromWalletResponse200(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/wallet/limit-amount');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetLimitAmountPerWeekFromWalletWithAllLabel(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/wallet/limit-amount');

        $this->assertSelectorTextContains('div.main h3', 'Définir ma limite de jeu');
        $this->assertCount(1, $crawler->filter('form input[name*="limitAmountPerWeek"]'));
    }

    public function testSetLimitAmountPerWeekFromWalletSuccess(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/wallet/limit-amount');
        $link = $crawler
            ->filter('div.main a')
            ->eq(0)
            ->link()
        ;

        $crawler = $client->click($link);
        $this->assertCount(1, $crawler->filter('form input[name*="limitAmountPerWeek"]'));
        $this->assertSelectorExists('form button[type="submit"]');

        $form = $crawler
            ->filter('form')
            ->eq(0)
            ->form();

        $form['wallet[limitAmountPerWeek]'] = '50';

        $client->submit($form);
        $this->assertResponseRedirects('/app/wallet/limit-amount');
    }

    public function testSetLimitAmountPerWeekFromWalletFail(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/wallet/limit-amount');
        $link = $crawler
            ->filter('div.main a')
            ->eq(0)
            ->link()
        ;

        $crawler = $client->click($link);
        $this->assertCount(1, $crawler->filter('form input[name*="limitAmountPerWeek"]'));
        $this->assertSelectorExists('form button[type="submit"]');

        $form = $crawler
            ->filter('form')
            ->eq(0)
            ->form();

        $form['wallet[limitAmountPerWeek]'] = '-50';

        $client->submit($form);
        $this->assertSelectorTextContains('', 'Limite incorrecte');
    }
}
