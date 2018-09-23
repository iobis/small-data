<?php

namespace App\Controller;

use App\Entity\Occurrence;
use App\Entity\Phylum;
use App\Entity\Species;
use App\Form\OccurrenceType;
use App\Repository\OccurrenceRepository;
use App\Repository\PhylumRepository;
use App\Repository\SpeciesRepository;
use Doctrine\Common\Persistence\ObjectManager;
use http\Env\Response;
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
     * @Route("/species/{idPhylum}", name="index_species_per_phylum")
     */
    public function indexSpecies($idPhylum = null, ObjectManager $manager)
    {

        $species = $manager->getRepository(Species::class)->findBy([], ['speciesNameWorms' => 'ASC']);
        $phyla = $manager->getRepository(Phylum::class)->findBy([], ['phylumNameWorms' => 'ASC']);

        $phylumToDisplay = $manager->getRepository(Phylum::class)->findOneBy(['id'=>$idPhylum]);
        dump ($phyla);
        return $this->render('species_occurrences/species.html.twig', [
//            'controller_name' => 'SmallDataController',
            'species' => $species,
            'phyla'=>$phyla,
            'phylumToDisplay'=>$phylumToDisplay
        ]);
    }


    /**
     * @Route("/species/{idSpecies}/details/", name="species_details")
     */
    public function detailSpecies(ObjectManager $manager, $idSpecies) // $wormsAphiaId
    {
            $species = $manager->getRepository(Species::class)->findOneBy(['id'=>$idSpecies]);
//        $species = $speciesRepository->findOneBy(['wormsAphiaId'=> $wormsAphiaId]);
//        $species = $this->getDoctrine()->getRepository(Species::class)
//                        ->findOneBy(['wormsAphiaId'=> $wormsAphiaId]);


    return $this->render('species_occurrences/species_details.html.twig', [
            'controller_name' => 'SmallDataController',
            'singleSpecies' => $species
        ]);
    }

    /**
     * @Route("/species/{idSpecies}/occurrences/",name="occurrences_list")
     */
//* @Route("/species/occurrences_{wormsAphiaId}/", name="occurrences_list")

    public function occurrencesSpecies( ObjectManager $manager, $idSpecies)
    {
        $species = $manager->getRepository(Species::class)->findOneBy(['id'=>$idSpecies]);
        $occurrences = $manager->getRepository(Occurrence::class)->findBy(['species'=>$species], ['eventDate' => 'ASC']);

        $intervalsWithFreqAndOccurrence = $this->renderOccurrenceIntervals($species, $occurrences, $manager);

        dump($intervalsWithFreqAndOccurrence);
        return $this->render('species_occurrences/occurrences.html.twig', [
            'controller_name' => 'SmallDataController',
            'singleSpecies' => $species,
            'occurrences' => $occurrences,
            'intervalsWithFreqAndOccurrences'=> $intervalsWithFreqAndOccurrence
        ]);

    }


    /**
     * @Route("/occurrence/{idOccurrence}/details", name="occurrence_details")
     */
    public function occurrenceDetails($idOccurrence, ObjectManager $manager){
        $occurrence = $this->getDoctrine()->getRepository((Occurrence::class))
            ->findOneBy(['id'=>$idOccurrence]);
        $singleSpecies = $occurrence->getSpecies();

        $occurrences = $manager->getRepository(Occurrence::class)->findBy(['species'=>$singleSpecies], []);
        $intervalsWithFreqAndOccurrence = $this->renderOccurrenceIntervals($singleSpecies, $occurrences, $manager);



        return $this->render('species_occurrences/occurrence_details.html.twig', [
            'occurrence'=>$occurrence,
            'singleSpecies' => $singleSpecies,
            'occurrences' => $occurrences,
            'intervalsWithFreqAndOccurrences'=> $intervalsWithFreqAndOccurrence


        ]);
    }

    public function renderOccurrenceIntervals ($species, $occurrences, ObjectManager $manager) {

//        $occurrences = $manager->getRepository(Occurrence::class)->findBy(['species'=>$species], ['eventDate' => 'ASC']);
//        dump($species, $occurrences);

        $yearsOccurrences = [];
        foreach ($occurrences as $occurrence){
            if($occurrence->getIsValidated()){
//            $yearOccurrence = [$occurrence->getId()=>$occurrence->getEventDate()->format("Y")];
                $yearOccurrence = $occurrence->getEventDate()->format("Y");

                array_push($yearsOccurrences, $yearOccurrence);
            }
        }
        arsort($yearsOccurrences);
        $minYear = min($yearsOccurrences);
        $maxYear = max($yearsOccurrences);
        $interval = ($maxYear-$minYear)/7;
        $intervalYears = [];
        $year=$minYear;
        for ($i=0; $i<8; $i++){

            array_push($intervalYears, intval($year));
            $year +=$interval;
        }
        $freq = array_count_values($yearsOccurrences);
        // We have now two arrays: $intervalYears gives the years chosen for the intervals (8 years have been chosen), $freq gives all the frequency of occurrences by year (all the years)
        //Now we produce an array with frequencies for each interval.
        $freqForInterval=[];
        $intervalsWithFreqAndOccurrence =[];
        for ($i=0; $i<sizeof($intervalYears); $i++){
            $numberOfRecordsForInterval = 0;
            $yearLower = $intervalYears[$i];
            if ($i<(sizeof($intervalYears)-1)){
                $arrayOccurrences=[];
                $yearUpperPlusOne = $intervalYears[$i+1];
                $yearUpper = $yearUpperPlusOne -1; //to be used if we chose to put it as a sting in the key of the array
                foreach ($freq as $year=>$frequency){
                    if ($year >=$yearLower && $year <$yearUpperPlusOne){
                        $numberOfRecordsForInterval+=$frequency;

                        foreach ($occurrences as $occurrence){
                            if($occurrence->getIsValidated()){
                                if($occurrence->getEventDate()->format("Y")== $year){

                                    array_push($arrayOccurrences, $occurrence);
                                }
                            }
                        }



                    }
                }
                array_push($freqForInterval, [(string)($yearLower.' to '.$yearUpper)=>$numberOfRecordsForInterval]);



                array_push($intervalsWithFreqAndOccurrence, [(string)($yearLower.'-'.$yearUpper), $numberOfRecordsForInterval, $arrayOccurrences]);





            } else {  //That's if we are in the last index of the array $intervalYears
                $arrayOccurrences=[];
                foreach ($freq as $year=>$frequency){
                    if ($year >=$yearLower){
                        $numberOfRecordsForInterval+=$frequency;
                        foreach ($occurrences as $occurrence){
                            if($occurrence->getIsValidated()){
                                if($occurrence->getEventDate()->format("Y")== $year){
                                    array_push($arrayOccurrences, $occurrence);
                                }
                            }
                        }
                    }
                }
                array_push($freqForInterval, [(string)($yearLower)=>$numberOfRecordsForInterval]);
                array_push($intervalsWithFreqAndOccurrence, [(string)($yearLower), $numberOfRecordsForInterval, $arrayOccurrences]);
            }
        }
        return $intervalsWithFreqAndOccurrence;



    }



}
