<?php

namespace App\Form;

use App\Controller\front_end\Destination;
use App\Entity\Continent;
use App\Entity\Pays;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DestinationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $p = $options['data']->getContinent();
        $v = $p->getPays();
        $m = $v[0]->getVille();

        $builder
            ->add('continent', EntityType::class, [
                'class' => Continent::class,
                'choice_label' => "nom",
                'placeholder' => "Sélectionner le continent"
            ])
            ->add('pays', EntityType::class, [
                'class' => Pays::class,
                'choice_label' => "nom",
                'placeholder' => "Selectionner le pays",
                'required' => false,
                'mapped' => false,
                'choices' => $m
            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => "nom",
                'placeholder' => "Selectionner la VILLE",
                'required' => false,
                'mapped' => false,
                'choices' => $v
            ])



        ;

        $builder->get('continent')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) {
                $form = $event->getForm();
                $continent = $form->getData();
                $pays = $continent->getPays()[0];
                if ($continent !== null) {
                    $this->addPaysField($form->getParent(), $continent);
                }
                if ($pays !==null) {
                    $this->addVilleField($form->getParent(), $pays);
                }
            }
        );
      
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function(FormEvent $event) {
                $continent = $event->getData()->getContinent();
                $pays = $event->getData()->getPays();
                $this->addPaysField($event->getForm(), $continent);
                if ($pays !== null) {
                    $this->addVilleField($event->getForm(), $pays);
                }
            
            }
        );
          
    }

    private function addPaysField(Form $form, Continent $continent) {
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'pays',
            EntityType::class,
            null,
            [
                'class' => Pays::class,
                'choice_label' => "nom",
                'placeholder' => "Sélectionner le Pays",
                'mapped' => false,
                'required' => false,
                'choices' => $continent->getPays(),
                'auto_initialize' => false
            ]
        );
        
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) {
                $form = $event->getForm();
                if ($form->getData() !== null) {
                    $this->addVilleField($form->getParent(), $form->getData());
                }
            }
        );

        $form->add($builder->getForm());
    }

    private function addVilleField(Form $form, Pays $pays ) {
        $form->add('ville', EntityType::class, [
            'class' => Ville::class,
            'label' => "Ville",
            'choice_label' => "nom",
            'placeholder' => "Sélectionner une ville",
            'mapped' => 'false',
            'required' => 'false',
            'choices' => $pays->getVilles()
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            "data_class" => Destination::class,
        ]);
    }
}
