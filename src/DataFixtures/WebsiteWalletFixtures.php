<?php

namespace App\DataFixtures;

use App\Entity\WebsiteWallet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WebsiteWalletFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $websiteWallet = new WebsiteWallet();
        $websiteWallet->initializeWallet();
        $manager->persist($websiteWallet);

        $manager->flush();
    }
}
