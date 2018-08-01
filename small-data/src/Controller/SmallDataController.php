<?php

namespace App\Controller;

use App\Entity\Occurrence;
use App\Entity\Species;
use App\Repository\OccurrenceRepository;
use App\Repository\SpeciesRepository;
use Doctrine\Common\Persistence\ObjectManager;
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

    /**
     * @Route("{id}/create_occurrence", name="occurrence_create")
     */
    public function formOccurrence(Species $species, Request $request, ObjectManager $objectManager){
        $occurrence = new Occurrence();

        $form = $this->createFormBuilder($occurrence)
                    ->add ('decimalLatitude')
                    ->getForm();

        return $this->render('species_occurrences/occurrence_create.html.twig',[
            'singleSpecies' => $species,
            'formOccurrence'=> $form->createView()
        ]);

    }

}
