<?php

namespace App\Form;

use App\Entity\Occurrence;
use App\Entity\Species;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class OccurrenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('species', EntityType::class, [
                'class'=>Species::class,
                'choice_label'=> 'speciesNameWorms'
            ])
            ->add('eventDate',DateType::class, [
                'widget'=>'single_text'
            ])
            ->add('vernacularName')
            ->add('scientificNameAtCollection')
            ->add('decimalLongitude')
            ->add('decimalLatitude')
            ->add('locality')
            ->add('locationId')
            ->add('occurrenceRemarks')
            ->add('associatedMediaUrl')
//            ->add('occurrenceCreatedAt')

        ;

//        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event){
//
//        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Occurrence::class,
        ]);
    }
}
