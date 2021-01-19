<?php

namespace App\DataFixtures;

use App\Entity\TypeOfBet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeOfBetFixtures extends Fixture
{
    public const TYPE_OF_BET_1 = '1N2';
    public const TYPE_OF_BET_2 = '1-2';
    public const TYPE_OF_BET_3 = 'over-under';
    public const TYPE_OF_BET_4 = 'handicap';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $typeOfBet1 = new TypeOfBet();
        $typeOfBet1->setBetType('1N2');
        $manager->persist($typeOfBet1);

        $typeOfBet2 = new TypeOfBet();
        $typeOfBet2->setBetType('1-2');
        $manager->persist($typeOfBet2);

        $typeOfBet3 = new TypeOfBet();
        $typeOfBet3->setBetType('over-under');
        $manager->persist($typeOfBet3);

        $typeOfBet4 = new TypeOfBet();
        $typeOfBet4->setBetType('handicap');
        $manager->persist($typeOfBet4);

        $this->addReference(self::TYPE_OF_BET_1, $typeOfBet1);
        $this->addReference(self::TYPE_OF_BET_2, $typeOfBet2);
        $this->addReference(self::TYPE_OF_BET_3, $typeOfBet3);
        $this->addReference(self::TYPE_OF_BET_4, $typeOfBet4);

        $manager->flush();
    }
}
