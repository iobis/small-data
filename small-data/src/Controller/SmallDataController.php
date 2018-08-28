<?php

namespace App\Controller;

use App\Entity\Occurrence;
use App\Entity\Species;
use App\Form\OccurrenceType;
use App\Repository\OccurrenceRepository;
use App\Repository\PhylumRepository;
use App\Repository\SpeciesRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;





class SmallDataController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function home(ObjectManager $manager)
    {
        $occurrences = $manager->getRepository(Occurrence::class)->findBy([]);
        $occurrencesNonValid = $manager->getRepository(Occurrence::class)->findBy(['isValidated'=>false]);

        return $this->render('species_occurrences/home.html.twig', [
            'occurrences'=>$occurrences,
            'occurrencesNonValid'=>$occurrencesNonValid
        ]);
    }

    /**
     * @Route("/species", name="index_species")
     * @Route("/species/{phylumNameWorms}", name="index_species_per_phylum")
     */
    public function indexSpecies(SpeciesRepository $speciesRepository, PhylumRepository $phylumRepository, $phylumNameWorms = null)
    {

        $species = $speciesRepository->findBy([], ['speciesNameWorms' => 'ASC']);
        $phyla = $phylumRepository->findBy([], ['phylumNameWorms' => 'ASC']);
        $phylumToDisplay = $phylumRepository->findOneBy(['phylumNameWorms'=>$phylumNameWorms]);
        dump ($phyla);
        return $this->render('species_occurrences/species.html.twig', [
//            'controller_name' => 'SmallDataController',
            'species' => $species,
            'phyla'=>$phyla,
            'phylumToDisplay'=>$phylumToDisplay
        ]);
    }


    /**
     * @Route("/species/{wormsAphiaId}/details/", name="species_details")
     */
    public function detailSpecies(Species $species) // $wormsAphiaId
    {

//        $species = $speciesRepository->findOneBy(['wormsAphiaId'=> $wormsAphiaId]);
//        $species = $this->getDoctrine()->getRepository(Species::class)
//                        ->findOneBy(['wormsAphiaId'=> $wormsAphiaId]);


    return $this->render('species_occurrences/species_details.html.twig', [
            'controller_name' => 'SmallDataController',
            'singleSpecies' => $species
        ]);
    }

    /**
     * @Route("/species/{wormsAphiaId}/occurrences/",name="occurrences_list")
     */
//* @Route("/species/occurrences_{wormsAphiaId}/", name="occurrences_list")

    public function occurrencesSpecies(Species $species, OccurrenceRepository $occurrenceRepository)
    {
        $occurrences = $occurrenceRepository->findBy(['species'=>$species], ['eventDate' => 'ASC']);
        dump($species, $occurrences);
        return $this->render('species_occurrences/occurrences.html.twig', [
            'controller_name' => 'SmallDataController',
            'singleSpecies' => $species,
            'occurrences' => $occurrences
        ]);

    }


    /**
     * @Route("/{wormsAphiaId}/occurrence/{idOccurrence}/details", name="occurrence_details")
     * @Route("/{wormsAphiaId}/occurrence/{idOccurrence}/details/{slug}", name="occurrence_details_satellite")
     */
    public function occurrenceDetails($idOccurrence, $slug = null){
        $occurrence = $this->getDoctrine()->getRepository((Occurrence::class))
            ->findOneBy(['id'=>$idOccurrence]);


        return $this->render('species_occurrences/occurrence_details.html.twig', [
            'occurrence'=>$occurrence,
            'satellite'=>$slug
        ]);
    }



}
