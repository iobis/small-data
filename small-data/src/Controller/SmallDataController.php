<?php

namespace App\Controller;

use App\Entity\Occurrence;
use App\Entity\Species;
use App\Form\OccurrenceType;
use App\Repository\OccurrenceRepository;
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
    public function home()
    {
        return $this->render('species_occurrences/home.html.twig', []);
    }

    /**
     * @Route("/species", name="index_species")
     */
    public function indexSpecies(SpeciesRepository $speciesRepository)
    {

        $species = $speciesRepository->findBy([], ['speciesNameWorms' => 'ASC']);

        return $this->render('species_occurrences/species.html.twig', [
//            'controller_name' => 'SmallDataController',
            'species' => $species
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





}
