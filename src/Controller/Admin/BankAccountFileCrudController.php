<?php

namespace App\Controller\Admin;

use App\Entity\BankAccountFile;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BankAccountFileCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BankAccountFile::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
