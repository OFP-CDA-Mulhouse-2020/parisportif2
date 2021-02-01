<?php

namespace App\Factory;

use App\Entity\BankAccountFile;

class BankAccountFileFactory
{
    public static function makeBankAccountFile(string $filename): BankAccountFile
    {
        $bankAccountFile = new BankAccountFile();
        $bankAccountFile->setName($filename);

        return $bankAccountFile;
    }
}
