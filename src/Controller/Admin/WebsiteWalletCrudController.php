<?php

namespace App\Controller\Admin;

use App\Entity\WebsiteWallet;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class WebsiteWalletCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WebsiteWallet::class;
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
