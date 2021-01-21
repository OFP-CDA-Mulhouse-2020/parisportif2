<?php

namespace App\Controller\Admin;

use App\Entity\Bet;
use App\Form\AddMoneyType;
use App\Form\BetType;
use App\Repository\BetRepository;
use App\Repository\ItemRepository;
use App\Repository\TypeOfPaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityBuiltEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\InsufficientEntityPermissionException;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Security\Permission;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class BetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Bet::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        // this action executes the 'renderInvoice()' method of the current CRUD controller
        $viewInvoice = Action::new('viewValidateBetPayment', 'Validate Bet Payment', 'fa fa-file-invoice')
            ->linkToCrudAction('renderValidateBetPayment');


        return $actions
            // ...
            ->add(Crud::PAGE_EDIT, $viewInvoice)

            ;
    }

    public function renderValidateBetPayment(
        AdminContext $context
    ): Response {

        $id = $context->getEntity()->getInstance()->getId();

        return  $this->redirectToRoute('app_cart_bet_payment', ['id' => $id]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('id', 'ID')->onlyOnIndex(),
            AssociationField::new('event'),
            AssociationField::new('typeOfBet'),
            DateTimeField::new('betLimitTime'),
            ArrayField::new('listOfOdds'),
            ArrayField::new('betResult'),
            BooleanField::new('betOpened')
        ];
    }
}
