<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Field\FileFormField;
use Symfony\Component\DomCrawler\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserProfileInformationControllerTest extends WebTestCase
{
    public function testGetUserInformationResponse200(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $client->request('GET', '/app/profile/information');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetUserInformationWithAllLabel(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/profile/information');

        $this->assertSelectorTextContains('div.main h3', 'Mes informations');
        $this->assertCount(1, $crawler->filter('form input[name="identity_disabled[lastName]"]'));
        $this->assertCount(1, $crawler->filter('form input[name="identity_disabled[firstName]"]'));
        $this->assertCount(1, $crawler->filter('form input[name="identity_disabled[birthDate]"]'));
        $this->assertSelectorExists('button.edit-btn1');


        $this->assertCount(1, $crawler->filter('form input[name="address_disabled[addressNumberAndStreet]"]'));
        $this->assertCount(1, $crawler->filter('form input[name="address_disabled[zipCode]"]'));
        $this->assertCount(1, $crawler->filter('form input[name="address_disabled[city]"]'));
        $this->assertCount(1, $crawler->filter('form input[name="address_disabled[country]"]'));
        $this->assertSelectorExists('button.edit-btn2');
    }

    public function testEditUserIdentity(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/profile/information');
        $this->assertResponseStatusCodeSame(200);

        $this->assertSelectorTextContains('div.main h3', 'Mes informations');
        $this->assertCount(1, $crawler->filter('form input[name="identity[lastName]"]'));
        $this->assertCount(1, $crawler->filter('form input[name="identity[firstName]"]'));
        $this->assertCount(1, $crawler->filter('form input[name="identity[birthDate]"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="justificatif"]'));
        $this->assertSelectorExists('form button[type="submit"]');
    }

    public function testEditUserIdentitySuccess(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/profile/information');
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler
            ->filter('form')
            ->eq(1)
            ->form();

        $form['identity[lastName]'] = 'cda';
        $form['identity[firstName]'] = 'ladji';
        $form['identity[birthDate]'] = '1995-12-12';

        $fileField = $form['identity[justificatif]'];
        assert($fileField instanceof FileFormField);
        $fileField->upload('tests/Data/unknown.jpg');

        /** @param Form  $form */
        $client->submit($form);

        $this->assertResponseRedirects('/app/profile/information');
    }


    public function testEditUserIdentityFail(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/profile/information');
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler
            ->filter('form')
            ->eq(1)
            ->form();

        $form['identity[lastName]'] = 'cda';
        $form['identity[firstName]'] = 'ladji';
        $form['identity[birthDate]'] = '1995-12-12';

        $fileField = $form['identity[justificatif]'];
        assert($fileField instanceof FileFormField);
        $fileField->upload('');

        /** @param Form  $form */
        $client->submit($form);

        $this->assertSelectorTextContains('', 'Vous devez fournir une pièce-jointe');
    }


    public function testEditUserAddress(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/profile/information');
        $this->assertResponseStatusCodeSame(200);

        $this->assertSelectorTextContains('div.main h3', 'Mes informations');
        $this->assertCount(1, $crawler->filter('form input[name="address_disabled[addressNumberAndStreet]"]'));
        $this->assertCount(1, $crawler->filter('form input[name="address_disabled[zipCode]"]'));
        $this->assertCount(1, $crawler->filter('form input[name="address_disabled[city]"]'));
        $this->assertCount(1, $crawler->filter('form input[name="address_disabled[country]"]'));


        $this->assertSelectorExists('form button[type="submit"]');
    }

    public function testEditUserAddressSuccess(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/profile/information');
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler
            ->filter('form')
            ->eq(3)
            ->form();

        $form['address[addressNumberAndStreet]'] = '8 rue des champs';
        $form['address[zipCode]'] = '75000';
        $form['address[city]'] = 'Paris';
        $form['address[country]'] = 'France';

        $client->submit($form);

        $this->assertResponseRedirects('/app/profile/information');
    }

    public function testEditUserAddressFail(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        assert($userRepository instanceof UserRepository);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        assert($testUser instanceof User);
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/profile/information');
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler
            ->filter('form')
            ->eq(3)
            ->form();

        $form['address[addressNumberAndStreet]'] = '8 rue des champs';
        $form['address[zipCode]'] = '7';
        $form['address[city]'] = 'Paris';
        $form['address[country]'] = 'France';

        $client->submit($form);

        $this->assertSelectorTextContains('', 'Code postal incorrect');
    }
}
