<?php

namespace App\Form;

use App\Entity\Occurrence;
use App\Entity\Species;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OccurrenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('occurrenceCreatedAt')
            ->add('species', EntityType::class, [
                'class'=>Species::class,
                'choice_label'=> 'speciesNameWorms'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Occurrence::class,
        ]);
    }
}
