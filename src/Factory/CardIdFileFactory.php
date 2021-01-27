<?php

namespace App\Factory;

use App\Entity\CardIdFile;

class CardIdFileFactory
{
    public static function makeCardIdFile(string $fileName): CardIdFile
    {
        $cardIdFile = new CardIdFile();
        $cardIdFile->setName($fileName);

        return $cardIdFile;
    }
}
