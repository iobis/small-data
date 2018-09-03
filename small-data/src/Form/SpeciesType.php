<?php

namespace App\Form;

use App\Entity\Phylum;
use App\Entity\Species;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpeciesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('wormsAphiaId')
            ->add('speciesNameWorms')
            ->add('phylum', EntityType::class, [
                'class'=>Phylum::class,
                'choice_label'=>'phylumNameWorms'
                ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Species::class,
        ]);
    }
}
