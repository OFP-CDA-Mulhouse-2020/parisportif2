<?php

namespace App\Controller\Admin;

use App\Admin\Field\MapField;
use App\Dto\BetDto;
use App\Entity\Bet;
use App\Form\BetType;
use App\Form\ResultEventType;
use App\Service\GenerateBetPaymentService;
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
use Symfony\Component\HttpFoundation\Response;

class BetCrudController extends AbstractCrudController
{
    private GenerateBetPaymentService $generateBetPaymentService;

    public function __construct(GenerateBetPaymentService $generateBetPaymentService)
    {
        $this->generateBetPaymentService = $generateBetPaymentService;
    }

    public static function getEntityFqcn(): string
    {
        return Bet::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        // this action executes the 'renderInvoice()' method of the current CRUD controller
        $setInvoice = Action::new('validateBetPayment', 'Validate Bet Payment', 'fa fa-file-invoice')
            ->displayIf(static function ($entity) {
                if (!$entity->isBetOpened() && count($entity->getBetResult()) > 0) {
                    return true;
                }
                return false;
            })
            ->linkToCrudAction('validateBetPayment')
        ;

        $setResult = Action::new('setResult', 'Set Result', 'fas fa-trophy')
            ->displayIf(static function ($entity) {
                if (!$entity->isBetOpened()) {
                    return true;
                }
                return false;
            })
            ->linkToCrudAction('setResult');

        return $actions
            ->add(Crud::PAGE_INDEX, $setInvoice)
            ->add(Crud::PAGE_INDEX, $setResult);
    }

    public function validateBetPayment(AdminContext $context): Response
    {
        $entityInstance = $context->getEntity()->getInstance();
        if ($entityInstance->isBetOpened()) {
            return $this->redirect($context->getReferrer());
        }
        $this->generateBetPaymentService->validateBetToPayment($entityInstance);

        return $this->redirect($context->getReferrer());
    }


    public function setResult(AdminContext $context): Response
    {

        $entityInstance = $context->getEntity()->getInstance();
        $resultEventForm =  $this->createForm(ResultEventType::class, $entityInstance);
        $resultEventForm->handleRequest($context->getRequest());

        if ($resultEventForm->isSubmitted() && $resultEventForm->isValid()) {
            $results = $context->getRequest()->request->get('result_event');
               $betResult = [];
            foreach ($results as $key => $result) {
                if ($result === "1") {
                    $betResult[] = $key;
                }
            }
            $entityInstance->setBetResult($betResult);
            $entityManager =  $this->getDoctrine()->getManager();
            $entityManager->persist($entityInstance);
            $entityManager->flush();
            $this->addFlash('success', 'Les résultats ont été enregistrés');

                return $this->redirect($context->getReferrer());
        }


        return  $this->render(
            'bundles/EasyAdminBundle/crud/custom_form_bet_result.html.twig',
            [
                'bet' => $entityInstance,
                'resultEventForm' => $resultEventForm->createView(),
            ]
        );
    }



    public function createEditForm(
        EntityDto $entityDto,
        KeyValueStore $formOptions,
        AdminContext $context
    ): FormInterface {
        $instance = $entityDto->getInstance();
        $listOfOdds = $instance->getListOfOdds();
        $listOfBetDto = [];

        foreach ($listOfOdds as $key => $odd) {
            $betDto = BetDto::build([$odd[0],$odd[1]]);
            $listOfBetDto[] = $betDto;
        }
        $instance->setOddsList($listOfBetDto);

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
            ->addCssClass('custom-badge')
                ->formatValue(function ($value, $entity) {
                    if ($value) {
                        return 'open';
                    }
                    return 'closed';
                });


        $betOpened2 = BooleanField::new('betOpened');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $event, $typeOfBet, $betLimitTime, $listOfOdds, $betOpened];
        } else {
            return [ $event, $typeOfBet, $betLimitTime, $oddsList, $betOpened2];
        }
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle(Crud::PAGE_INDEX, 'Liste des paris')
            ->overrideTemplate('crud/edit', 'bundles/EasyAdminBundle/crud/custom_edit.html.twig')
            ->overrideTemplate('crud/index', 'bundles/EasyAdminBundle/crud/custom_index.html.twig')
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

        $entityInstance->setListOfOdds($list);

        return $entityInstance;
    }
}
