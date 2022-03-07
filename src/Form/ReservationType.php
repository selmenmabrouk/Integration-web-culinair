<?php

namespace App\Form;

use App\Entity\Guide;
use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Num_vol')
            ->add('Destination')
            ->add('Date_depart' , DateType::class)
            ->add('Date_arrivee' , DateType::class)
            ->add('Adulte')
            ->add('Enfant')
            ->add('Guide' , EntityType::class ,[
                    'class' => Guide::class , 
                    'choice_label' => 'prenom' ,
                    'expanded'=> false ,
                    'multiple'=>false
            ]
                     )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
