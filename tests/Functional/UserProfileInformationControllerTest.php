<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserProfileInformationControllerTest extends WebTestCase
{
    public function testGetUserInformationResponse200(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $client->request('GET', '/app/profile/information');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetUserInformationWithAllLabel(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/profile/information');

        $this->assertSelectorTextContains('div.main h3', 'Mes informations');
        $this->assertCount(1, $crawler->filter('form input[name*="lastName"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="firstName"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="birthDate"]'));

        $this->assertCount(1, $crawler->filter('form input[name*="addressNumberAndStreet"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="zipCode"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="city"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="country"]'));
    }

    public function testEditUserIdentity(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/profile/information');
        $this->assertResponseStatusCodeSame(200);

        $link = $crawler
            ->filter('div.main a')
            ->eq(0)
            ->link()
        ;

        $crawler = $client->click($link);

        $this->assertSelectorTextContains('div.main h3', 'Mes informations');
        $this->assertCount(1, $crawler->filter('form input[name*="lastName"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="firstName"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="birthDate"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="justificatif"]'));
        $this->assertSelectorExists('form button[type="submit"]');
    }

    public function testEditUserIdentitySuccess(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/profile/edit/identity');
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler
            ->filter('form')
            ->eq(0)
            ->form();

        $form['identity[lastName]'] = 'cda';
        $form['identity[firstName]'] = 'ladji';
        $form['identity[birthDate]'] = '1995-12-12';
  //      $form['identity[justificatif]'] = 'ladji.cda@test.com';

   //     $crawler = $client->submit($form);

    //    $this->assertResponseRedirects('/app/profile/information');
    }


    public function testEditUserIdentityFail(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/profile/edit/identity');
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler
            ->filter('form')
            ->eq(0)
            ->form();

        $form['identity[lastName]'] = 'cda';
        $form['identity[firstName]'] = 'ladji';
        $form['identity[birthDate]'] = '1995-12-12';

        $crawler = $client->submit($form);

        $this->assertSelectorTextContains('', 'The file could not be found.');
    }


    public function testEditUserAddress(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/profile/information');
        $this->assertResponseStatusCodeSame(200);

        $link = $crawler
            ->filter('div.main a')
            ->eq(1)
            ->link()
        ;

        $crawler = $client->click($link);

        $this->assertSelectorTextContains('div.main h3', 'Mes informations');
        $this->assertCount(1, $crawler->filter('form input[name*="addressNumberAndStreet"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="zipCode"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="city"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="country"]'));

        $this->assertSelectorExists('form button[type="submit"]');
    }

    public function testEditUserSuccess(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/profile/edit/address');
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler
            ->filter('form')
            ->eq(1)
            ->form();

        $form['address[addressNumberAndStreet]'] = '8 rue des champs';
        $form['address[zipCode]'] = '75000';
        $form['address[city]'] = 'Paris';
        $form['address[country]'] = 'France';

        $crawler = $client->submit($form);

        $this->assertResponseRedirects('/app/profile/information');
    }

    public function testEditUserAddressFail(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/profile/edit/address');
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler
            ->filter('form')
            ->eq(1)
            ->form();

        $client->submit($form);

        $form['address[addressNumberAndStreet]'] = '8 rue des champs';
        $form['address[zipCode]'] = '2';
        $form['address[city]'] = 'Paris';
        $form['address[country]'] = 'France';

   //     $this->assertSelectorTextContains('', 'Code postal incorrect');
    }
}
