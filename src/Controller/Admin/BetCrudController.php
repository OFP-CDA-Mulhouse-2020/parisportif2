<?php

namespace App\Controller\Admin;

use App\Admin\Field\MapField;
use App\Dto\BetDto;
use App\Dto\ResultDto;
use App\Entity\Bet;
use App\Form\BetType;
use App\Form\ResultType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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

        $new = Action::new('test', 'Edition', '')
            ->linkToCrudAction('test');

        return $actions
            ->add(Crud::PAGE_EDIT, $viewInvoice);
    }

    public function renderValidateBetPayment(
        AdminContext $context
    ): Response {

        $id = $context->getEntity()->getInstance()->getId();

        return  $this->redirectToRoute('app_cart_bet_payment', ['id' => $id]);
    }

    public function createEditForm(
        EntityDto $entityDto,
        KeyValueStore $formOptions,
        AdminContext $context
    ): FormInterface {
        $instance = $entityDto->getInstance();
        $listOfOdds = $instance->getListOfOdds();
        $resultBetDto = [];
        $listOfBetDto = [];

        foreach ($listOfOdds as $key => $odd) {
            $betDto = BetDto::build([$odd[0],$odd[1]]);
            $listOfBetDto[] = $betDto;
            $resultDto = new ResultDto();
            $resultDto->setName($key . '-' . $odd[0]);
            $resultBetDto[] = $resultDto;
        }
        $instance->setResultList($resultBetDto);
        $instance->setOddsList($listOfBetDto);
       // dd($instance->getEvent());

        return $this->createEditFormBuilder($entityDto, $formOptions, $context)->getForm();
    }


    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id', 'ID');
        $event = AssociationField::new('event');
        $typeOfBet = AssociationField::new('typeOfBet');
        $betLimitTime = DateTimeField::new('betLimitTime');
        $listOfOdds = CollectionField::new('listOfOdds');
        $oddsList = CollectionField::new('oddsList')->setEntryType(BetType::class);

        $betOpened = MapField::new('betOpened')
            ->formatValue(function ($value, $entity) {
                if ($value) {
                    return 'open';
                }
                return 'closed';
            });

        $betOpened2 = BooleanField::new('betOpened');
        $betResult = CollectionField::new('resultList')->setEntryType(ResultType::class);


        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $event, $typeOfBet, $betLimitTime, $listOfOdds, $betOpened];
        } else {
            return [ $event, $typeOfBet, $betLimitTime, $oddsList, $betOpened2, $betResult];
        }
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle(Crud::PAGE_INDEX, 'Liste des paris')
            ->overrideTemplate('crud/edit', 'bundles/EasyAdminBundle/crud/custom_edit.html.twig')
            ;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Bet $entityInstance
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance = $this->processBeforePersist($entityInstance);

        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Bet $entityInstance
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance = $this->processBeforePersist($entityInstance);

        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    private function processBeforePersist(Bet $entityInstance): Bet
    {
        $oddsList = $entityInstance->getOddsList();

        $list = [];
        for ($i = 0; $i < count($oddsList); $i++) {
            $list[$i] = [$oddsList[$i]->getName(), $oddsList[$i]->getOdds()];
        }

        if (!$entityInstance->isBetOpened()) {
            $resultList = $entityInstance->getResultList();
            $entityInstance->setBetResult(array_keys($resultList));
        }
        $entityInstance->setListOfOdds($list);

        return $entityInstance;
    }
}
