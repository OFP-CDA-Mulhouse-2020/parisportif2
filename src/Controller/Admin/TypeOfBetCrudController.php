<?php

namespace App\Controller\Admin;

use App\Entity\TypeOfBet;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TypeOfBetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeOfBet::class;
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
