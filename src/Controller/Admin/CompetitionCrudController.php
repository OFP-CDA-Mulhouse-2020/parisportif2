<?php

namespace App\Controller\Admin;

use App\Entity\Competition;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CompetitionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Competition::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->onlyOnIndex(),
            TextField::new('name'),
            DateTimeField::new('startAt'),
            DateTimeField::new('endAt'),
            AssociationField::new('event'),

        ];
    }
}
