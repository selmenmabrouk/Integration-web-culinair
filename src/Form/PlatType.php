<?php

namespace App\Form;

use App\Entity\Plat;
use App\Entity\Restaurant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('Type_cuisine')
            ->add('Description',TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
            ])
            ->add('Prix')
            ->add('Restau',EntityType::class,
                [
                    'class'=>Restaurant::class,
                    'choice_label'=>function (Restaurant $restaurant) {
                        return $restaurant->getNom();},
                    'multiple'=>true,
                     'expanded' => true])
            ->add('Photo',FileType::class, [
                'label' => 'Photo',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-uploads the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/*',

                        ],
                        'mimeTypesMessage' => 'Please uploads a valid PDF document',
                    ])
                ],
            ])
            ->add('valider',SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }
}
