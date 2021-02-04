<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class SuspendAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('suspendType', ChoiceType::class, [
                'mapped' => false,
                'label' => false,
                'choices'  => array(
                    '-- Choisir un type d\'exclusion --' => null,
                    'Exclusion temporaire' => 1,
                    'Exclusion définitive' => 2,
                )
            ])

            ->add('suspendAt', DateType::class, [
                'mapped' => false,
                'label' => false,
                'widget' => 'single_text',
                'required' => false,
//                'attr' => [
//                    'min' => "now + 7 days",
//                ],

            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Désactiver mon compte',
                'attr' => [
                    'class' => 'btn btn-danger btn-block',
                ]
            ]);
    }
//
//    public function configureOptions(OptionsResolver $resolver): void
//    {
//        $resolver->setDefaults([
//            'validation_groups' => ['suspend'],
//        ]);
//    }
}
