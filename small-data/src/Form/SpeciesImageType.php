<?php

namespace App\Form;

use App\Entity\SpeciesImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpeciesImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('speciesImageName', FileType::class, ['label'=>'Upload the image for the species'])
            ->add('isForDisplay')
            ->add('isMain')
//            ->add('species')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SpeciesImage::class,
        ]);
    }
}
