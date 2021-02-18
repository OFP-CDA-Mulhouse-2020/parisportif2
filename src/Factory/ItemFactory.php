<?php

namespace App\Factory;

use App\Entity\Bet;
use App\Entity\Item;

class ItemFactory
{
    public static function makeItem(Bet $bet, int $expectedResult):Item
    {

        $odds = $bet->getListOfOdds()[$expectedResult];

        $item = new Item($bet);
        $item->setExpectedBetResult($expectedResult);
        $item->isModifiedRecordedOdds($odds[1]);
        $item->isModifiedAmount(5);

        return $item;
    }
}
