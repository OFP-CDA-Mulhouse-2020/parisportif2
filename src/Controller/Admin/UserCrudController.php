<?php

namespace App\Controller\Admin;

use App\Admin\Field\MapField;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\FormInterface;

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

    public function createEditForm(
        EntityDto $entityDto,
        KeyValueStore $formOptions,
        AdminContext $context
    ): FormInterface {
        $instance = $entityDto->getInstance();

        if ($instance->isActive()) {
            $instance->setActivateDto(true);
        }
        if ($instance->isSuspended()) {
            $instance->setSuspendedDto(true);
            $instance->setEndSuspendAtDto($instance->getEndSuspendedAt());
        }
        if ($instance->isDeleted()) {
            $instance->setDeletedDto(true);
        }
        return $this->createEditFormBuilder($entityDto, $formOptions, $context)->getForm();
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
        $userSuspended = MapField::new('suspended')
            ->addCssClass('custom-badge')
            ->formatValue(
                function ($value, $entity) {
                    if ($value) {
                        return 'yes';
                    }
                    return 'no';
                }
            );
        $userDeleted = MapField::new('deleted')
            ->addCssClass('custom-badge')
        ->formatValue(
            function ($value, $entity) {
                if ($value) {
                    return 'yes';
                }
                return 'no';
            }
        );

        $userActiveDto = BooleanField::new('activateDto');
        $userSuspendedDto = BooleanField::new('suspendedDto');
        $userDeletedDto = BooleanField::new('deletedDto');
        $userSuspendedAtDto = DateTimeField::new('endSuspendAtDto');

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
                $userSuspended,
                $userDeleted,
            ];
        } else {
            return [
                $lastName,
                $firstName,
                $email,
                $roles,
                $birthDate,
                $createAt,
                $userActiveDto,
                $userSuspendedDto,
                $userSuspendedAtDto,
                $userDeletedDto,
            ];
        }
    }


    /**
     * @param EntityManagerInterface $entityManager
     * @param User $entityInstance
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance = $this->processBeforePersist($entityInstance);

        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param User $entityInstance
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance = $this->processBeforePersist($entityInstance);

        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    private function processBeforePersist(User $entityInstance): User
    {

        if ($entityInstance->isActivateDto() && !$entityInstance->isActive()) {
            $entityInstance->activate();
        }
        if (!$entityInstance->isActivateDto() && $entityInstance->isActive()) {
             $entityInstance->deactivate();
        }
        if ($entityInstance->isDeletedDto() && !$entityInstance->isDeleted()) {
            $entityInstance->deactivate();
            $entityInstance->delete();
        }
        if (!$entityInstance->isDeletedDto() && $entityInstance->isDeleted()) {
             $entityInstance->undelete();
        }

        if ($entityInstance->isSuspendedDto() && !$entityInstance->isSuspended()) {
            $entityInstance->endSuspend($entityInstance->getEndSuspendAtDto());
        }

        if (!$entityInstance->isSuspendedDto() && $entityInstance->isSuspended()) {
            $entityInstance->unsuspended();
        }

       // dd($entityInstance);

        return $entityInstance;
    }
}
