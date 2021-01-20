<?php

namespace App\Controller\Admin;

use App\Entity\Bet;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Bet::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('id', 'ID')->onlyOnIndex(),
            AssociationField::new('event'),
            AssociationField::new('typeOfBet'),
            DateTimeField::new('betLimitTime'),
            ArrayField::new('listOfOdds'),
            BooleanField::new('betOpened'),

        ];
    }
}
