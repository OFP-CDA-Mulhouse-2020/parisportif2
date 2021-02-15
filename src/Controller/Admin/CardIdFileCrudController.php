<?php

namespace App\Controller\Admin;

use App\Entity\CardIdFile;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CardIdFileCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CardIdFile::class;
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
