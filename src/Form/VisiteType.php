<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VisiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pays', TextType::class, [
                "label" => "pays",
                "disabled" => true,
            ])

            ->add('idPays', TextType::class, [
                "label" => "idPays"
            ])

            ->add('remarque', TextType::class, [
                "label" => "remarque",
                "required" => false,
                "mapped" => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
