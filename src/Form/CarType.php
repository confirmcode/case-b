<?php

namespace App\Form;

use App\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('manufacturer', TextType::class, ['attr' => ['placeholder' => 'Manufacturer','class'=>'form-control form-control-sm']])
            ->add('model', TextType::class, ['attr' => ['placeholder' => 'Model','class'=>'form-control form-control-sm']])
            ->add('license_plate', TextType::class, ['attr' => ['placeholder' => 'License Plate','class'=>'form-control form-control-sm']])
            ->add('save', SubmitType::class, ['label' => 'Save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
