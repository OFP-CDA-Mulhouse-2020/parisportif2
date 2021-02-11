<?php

namespace App\Controller\Admin;

use App\Admin\Field\MapMkField;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->onlyOnIndex(),
            TextField::new('lastName'),
            TextField::new('firstName'),
            TextField::new('email'),
            ArrayField::new('roles'),
            DateTimeField::new('birth_date'),
            DateTimeField::new('create_at'),
            BooleanField::new('active'),
            DateTimeField::new('active_at'),
            BooleanField::new('suspended'),
            BooleanField::new('deleted'),
            //end_suspend_at
            //deleted_at
            AssociationField::new('wallet'),

            // Mes Fields Custom
            $activeUser = MapMkField::new('active')
                ->formatValue(function ($value, $entity) {
                    if ($value) {
                        return 'open';
                    }
                        return 'closed';
                }),
            $suspendedUser = MapMkField::new('suspended')
                ->formatValue(
                    function ($value, $entity) {
                        if ($value) {
                            return 'open';
                        }
                        return 'closed';
                    }
                ),
            $DeletedUser = MapMkField::new('deleted')
                ->formatValue(
                    function ($value, $entity) {
                        if ($value) {
                            return 'open';
                        }
                        return 'closed';
                    }
                )
        ];
    }
}
