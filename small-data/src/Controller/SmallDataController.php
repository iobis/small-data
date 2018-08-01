<?php

namespace App\Controller;

use App\Entity\Occurrence;
use App\Entity\Species;
use App\Repository\OccurrenceRepository;
use App\Repository\SpeciesRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;



class SmallDataController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('species_occurrences/home.html.twig', []);
    }

    /**
     * @Route("/species", name="index_species")
     */
    public function indexSpecies(SpeciesRepository $speciesRepository)
    {

        $species = $speciesRepository->findAll();

        return $this->render('species_occurrences/species.html.twig', [
//            'controller_name' => 'SmallDataController',
            'species' => $species
        ]);
    }


    //     @Entity("species", expr="repository.find(worms_aphia_id)")...CHECK TO MAKE IT WORK with {worms_aphia_id)

    /**
     * @Route("/species_{id}/details", name="species_details")
     */
    public function detailSpecies(Species $species)
    {

        return $this->render('species_occurrences/species_details.html.twig', [
            'controller_name' => 'SmallDataController',
            'singleSpecies' => $species
        ]);
    }

    /**
     * @Route("/species_{id}/occurrences", name="occurrences_list")
     */
    public function occurrencesSpecies(Species $species, OccurrenceRepository $occurrenceRepository)
    {
        $occurrences = $occurrenceRepository->findBy([], ['eventDate' => 'ASC']);
        return $this->render('species_occurrences/occurrences.html.twig', [
            'controller_name' => 'SmallDataController',
            'singleSpecies' => $species,
            'occurrences' => $occurrences
        ]);
    }

//    /**
//     * @Route("{worms_aphia_id}/create_occurrence", name="occurrence_create")
//     *
//     *
//     */
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

    /**
     * @Route("{id}/create_occurrence", name="occurrence_create")
     */
    public function formOccurrence(Species $species, Request $request, ObjectManager $objectManager){
        $occurrence = new Occurrence();

        $form = $this->createFormBuilder($occurrence)
                    ->add('eventDate',DateType::class, [
                        'widget'=>'single_text'
                    ])
                    ->add('vernacularName')
                    ->add('scientificNameAtCollection')
                    ->add ('decimalLatitude')
                    ->add ('decimalLongitude')
                    ->add('locality')
                    ->add('locationId')
                    ->add('occurrenceRemarks')
                    ->add('associatedMediaUrl')
                    ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $occurrence->setOccurrenceCreatedAt(new \DateTime())
                        ->setSpecies($species);


            $objectManager->persist($occurrence);
            $objectManager->flush();
            return $this->redirectToRoute('occurrences_list', ['id'=> $species->getId()]);

            ;
        }

        return $this->render('species_occurrences/occurrence_create.html.twig',[
            'singleSpecies' => $species,
            'formOccurrence'=> $form->createView()
        ]);

    }

    /**
     * @Route("{id}/edit_occurrence", name="occurrence_edit")
     */
    public function formOccurrenceEdit(Occurrence $occurrence, Request $request, ObjectManager $objectManager){

        $form = $this->createFormBuilder($occurrence)
            ->add('eventDate',DateType::class, [
                'widget'=>'single_text'
            ])
            ->add('vernacularName')
            ->add('scientificNameAtCollection')
            ->add ('decimalLatitude')
            ->add ('decimalLongitude')
            ->add('locality')
            ->add('locationId')
            ->add('occurrenceRemarks')
            ->add('associatedMediaUrl')
            ->add('species', EntityType::class, [
                'class'=>Species::class,
                'choice_label'=> 'speciesNameWorms'
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
//            $occurrence->setOccurrenceCreatedAt(new \DateTime())
//            $species = new Species;


            $objectManager->persist($occurrence);
            $objectManager->flush();
            return $this->redirectToRoute('occurrences_list', ['id'=> $occurrence->getSpecies()->getId()]);

            ;
        }

        return $this->render('species_occurrences/occurrence_edit.html.twig',[
            'occurrence' => $occurrence,
            'formOccurrenceEdit'=> $form->createView()
        ]);

}


}
