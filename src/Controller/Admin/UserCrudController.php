<?php

namespace App\Controller\Admin;

use App\Admin\Field\MapField;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        // ...
        ->overrideTemplate('crud/index', 'bundles/EasyAdminBundle/crud/custom_index.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id', 'ID')->onlyOnIndex();
        $lastName = TextField::new('lastName');
        $firstName = TextField::new('firstName');
        $email = TextField::new('email');
        $roles = ArrayField::new('roles');
        $birthDate = DateTimeField::new('birthDate');
        $createAt = DateTimeField::new('createAt');
        $userActive =  MapField::new('active')
            ->addCssClass('custom-badge')
            ->formatValue(function ($value, $entity) {
                if ($value) {
                    return 'yes';
                }
                return 'no';
            });
        $userSuspend = MapField::new('suspended')
            ->addCssClass('custom-badge')
            ->formatValue(
                function ($value, $entity) {
                    if ($value) {
                        return 'yes';
                    }
                    return 'no';
                }
            );
        $userDelete = MapField::new('deleted')
            ->addCssClass('custom-badge')
        ->formatValue(
            function ($value, $entity) {
                if ($value) {
                    return 'yes';
                }
                return 'no';
            }
        );

        if (Crud::PAGE_INDEX === $pageName) {
            return [
                $id,
                $lastName,
                $firstName,
                $email,
                $roles,
                $birthDate,
                $createAt,
                $userActive,
                $userSuspend,
                $userDelete
            ];
        } else {
            return [
                $lastName,
                $firstName,
                $email,
                $roles,
                $birthDate,
                $createAt,
                $userActive,
                $userSuspend,
                $userDelete
            ];
        }
    }
}
