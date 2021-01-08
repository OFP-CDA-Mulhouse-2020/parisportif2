<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserProfileControllerTest extends WebTestCase
{
    public function testUserProfileResponse200(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $client->request('GET', '/app/user/profile');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testUserProfileWithAllLabel(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile');
        $this->assertResponseStatusCodeSame(200);

        $this->assertEquals(1, $crawler->filter('div.left-side-menu')->count());
        $this->assertEquals(13, $crawler->filter('div.left-side-menu li')->count());

        $this->assertSelectorTextContains('ul li a', 'Mon profil');
        $this->assertSelectorTextContains('', 'Mon portefeuille');
        $this->assertSelectorTextContains('', 'Aide');
        $this->assertSelectorTextContains('', 'Nous contacter');

        $this->assertEquals(1, $crawler->filter('div.right-side-menu')->count());
        $this->assertEquals(2, $crawler->filter('div.right-side-menu li')->count());

        $this->assertEquals(1, $crawler->filter('div.main')->count());
    }

    public function testUserProfileConnexion(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/connexion');
        $this->assertResponseStatusCodeSame(200);

        $this->assertSelectorTextContains('div.main h3', 'Connexion');
        $this->assertCount(1, $crawler->filter('form input[name*="email"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="plainPassword"]'));
    }

    public function testUserProfileEditMail(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/connexion');
        $this->assertResponseStatusCodeSame(200);

        $link = $crawler
            ->filter('div.main a')
            ->eq(0)
            ->link()
        ;

        $crawler = $client->click($link);

        $this->assertSelectorTextContains('div.main h3', 'Connexion');
        $this->assertCount(1, $crawler->filter('form input[name*="oldEmail"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="newEmail"]'));
        $this->assertSelectorExists('form button[type="submit"]');
    }

    public function testUserProfileEditPassword(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/connexion');
        $this->assertResponseStatusCodeSame(200);

        $link = $crawler
            ->filter('div.main a')
            ->eq(1)
            ->link()
        ;

        $crawler = $client->click($link);

        $this->assertSelectorTextContains('div.main h3', 'Connexion');
        $this->assertCount(1, $crawler->filter('form input[name*="edit_password[oldPassword]"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="edit_password[plainPassword][first]"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="edit_password[plainPassword][second]"]'));

        $this->assertSelectorExists('form button[type="submit"]');
    }

    public function testUserProfileEditPasswordSuccess(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/edit/password');
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler
            ->filter('form')
            ->eq(1)
            ->form();

        $form['edit_password[oldPassword]'] = 'M1cdacda8';
        $form['edit_password[plainPassword][first]'] = 'M1cdacda8';
        $form['edit_password[plainPassword][second]'] = 'M1cdacda8';

        $crawler = $client->submit($form);

        $this->assertResponseRedirects('/app/user/profile/connexion');
    }

    public function testUserProfileEditPasswordFailOldPassword(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/edit/password');
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler
            ->filter('form')
            ->eq(1)
            ->form();

        $form['edit_password[oldPassword]'] = 'M1cdacda10';
        $form['edit_password[plainPassword][first]'] = 'M1cdacda10';
        $form['edit_password[plainPassword][second]'] = 'M1cdacda10';

        $client->submit($form);

        $this->assertSelectorTextContains('', 'Ancien mot de passe incorrect');
    }

    public function testUserProfileEditPasswordFailNewPassword(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/edit/password');
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler
            ->filter('form')
            ->eq(1)
            ->form();

        $form['edit_password[oldPassword]'] = 'M1cdacda8';
        $form['edit_password[plainPassword][first]'] = 'M1cdacda10';
        $form['edit_password[plainPassword][second]'] = 'M1cdacda11';

        $client->submit($form);

        $this->assertSelectorTextContains('', 'Les deux mots de passe doivent être identiques');
    }

    public function testUserProfileIdentity(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/information');
        $this->assertResponseStatusCodeSame(200);

        $this->assertSelectorTextContains('div.main h3', 'Mes informations');
        $this->assertCount(1, $crawler->filter('form input[name*="lastName"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="firstName"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="birthDate"]'));

        $this->assertCount(1, $crawler->filter('form input[name*="addressNumberAndStreet"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="zipCode"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="city"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="country"]'));
    }

    public function testUserProfileActivation(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $client->request('GET', '/app/user/profile/activation');
        $this->assertResponseStatusCodeSame(200);

        $this->assertSelectorTextContains('div.main h4', 'Activation du compte');
        $this->assertSelectorTextContains('div.main h4:nth-of-type(2)', 'Désactivation du compte');
        $this->assertSelectorTextContains('div.main a', 'Désactiver mon compte');
    }

    public function testUserProfileSuspend(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/suspend');
        $this->assertResponseStatusCodeSame(200);

        $this->assertSelectorTextContains('div.main h4', 'Description');
        $this->assertSelectorTextContains('div.main h4:nth-of-type(2)', 'Quel type d\'auto exclusion souhaitez vous ?');
        $this->assertCount(1, $crawler->filter('form select[name*="suspendType"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="suspendAt"]'));
        $this->assertSelectorExists('form button[type="submit"]');
    }
}
