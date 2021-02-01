<?php

namespace App\Form;

use App\Entity\BankAccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class BankAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ibanCode', TextType::class)
            ->add('bicCode', TextType::class)
            ->add('ribJustificatif', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF, PNG or JPEG document',
                    ])
                ],
            ])

            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BankAccount::class,
            'validation_groups' => ['bankAccount'],
            'error_mapping' => [
                'error_type_1' => 'Vous devez fournir un justificatif',
            ],
        ]);
    }
}
