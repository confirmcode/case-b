<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingCarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('car', EntityType::class, [
                'class' => Car::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('c')
                        ->where('c.userId = 0');
                },
                'choice_label' => function (Car $car): string {
                    return $car->getManufacturer()
                         . ' ' . $car->getModel()
                         . ' - ' . $car->getLicensePlate()
                    ;
                },
                'label' => 'Автомобиль'
            ])
            ->add('submit', SubmitType::class, ['label'=>'Бронировать'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
