<?php

namespace App\Form;

use App\Dto\ResultDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultFieldType extends AbstractType
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('result', CheckboxType::class)

        ;
    }
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
       // dd($options);

        // pass the form type option directly to the template
      //  $view->vars[0] = $options['is_extended_address'];

        // make a database query to find possible notifications related to postal addresses (e.g. to
        // display dynamic messages such as 'Delivery to XX and YY states will be added next week!')
     //   $view->vars['notification'] = $this->entityManager->find('...');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

          //  'data_class' => ResultDto::class,
            ]);
    }
}
