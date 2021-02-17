<?php

namespace App\Form;

use App\Dto\ResultDto;
use App\Entity\Bet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $bet = $builder->getData();
        $listOfOdds = $bet->getListOfOdds();

        foreach ($listOfOdds as $key => $odds) {
            $builder->add($key, CheckboxType::class, [
                    'mapped' => false,
                    'required' => false,
                    'label' => $odds[0] . ' - ' . $odds[1],
                ]);
        }

        $builder
        ->add('Valider', SubmitType::class, [
        'attr' => [
            'class' => 'btn btn-success btn-block'
        ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

          //  'data_class' => ResultDto::class,
            ]);
    }
}
