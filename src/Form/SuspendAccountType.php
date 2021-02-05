<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class SuspendAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('suspendType', ChoiceType::class, [
                'mapped' => false,
                'label' => false,
                'required' => false,
                'choices'  => [
                    '-- Choisir un type d\'exclusion --' => null,
                    'Exclusion temporaire' => 1,
                    'Exclusion définitive' => 2,
                ],
                'constraints' => [
                    new NotBlank([
                        'groups' => 'suspend',
                        'message' => 'Vous devez choisir un type d\'exclusion.'
                    ]),
                ],

            ])

            ->add('suspendUntil', DateType::class, [
                'mapped' => false,
                'label' => false,
                'widget' => 'single_text',
                'required' => false,
                'constraints' => [
                    new GreaterThanOrEqual([
                        'groups' => 'suspend',
                        'value' => '+7 days',
                        'message' => 'La durée de suspension doit être supérieur à 1 semaine .'
                    ]),
                    new NotBlank([
                        'groups' => 'suspend',
                        'message' => 'Vous devez définir une date de suspension d\'au moins 1 semaine .'
                    ]),
                ],

            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Désactiver mon compte',
                'attr' => [
                    'class' => 'btn btn-danger btn-block',
                ]
            ]);
    }
//
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
//            'data_class' => User::class,
            'validation_groups' => ['suspend'],
        ]);
    }
}
