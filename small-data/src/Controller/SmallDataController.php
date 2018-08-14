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

//    * @Entity("singleSpecies", expr="repository.find(wormsAphiaId)")
//     * @Route("occurrence/{wormsAphiaId}/{id}/edit", name="occurrence_edit")
    /**
     * @Route("/{wormsAphiaId}/create_occurrence", name="occurrence_create")
     * @Route("/{wormsAphiaId}/occurrence/{id}/editFields", name="occurrence_edit")
     * @Route("/{wormsAphiaId}/occurrence/{id}/{mode}", name="occurrence_edit_species")
     */
    public function formOccurrence($wormsAphiaId, $id = null, $mode = null, Occurrence $occurrence = null,
                                   Request $request, ObjectManager $objectManager){
        $singleSpecies = $this->getDoctrine()->getRepository(Species::class)
            ->findOneBy(['wormsAphiaId'=> $wormsAphiaId]);

        // At this stage, the whole $occurrence object is null...(only for route "occurrence_create")
        //i.e. no instance, and the functions of the object cannot be accessed
        //Therefore create an instance.
        $flagNewOccurrence = FALSE;
        $flagEditSpecies = FALSE;

        if (!$occurrence) {
            $flagNewOccurrence = TRUE;
            $occurrence = new Occurrence();
        }
        if($mode === "editSpecies") {
            $flagEditSpecies = TRUE;
        }

        $form = $this->createForm(OccurrenceType::class, $occurrence);
        if($flagEditSpecies || $flagNewOccurrence) {
            $form->add('species', EntityType::class, [
                'class'=>Species::class,
                'choice_label'=> 'speciesNameWorms'
            ]);
        }

            $form->handleRequest($request);
        dump($occurrence, $singleSpecies, $request, (bool)$occurrence, $flagNewOccurrence, $mode );


        if($form->isSubmitted() && $form->isValid()) {
            if ( $flagNewOccurrence){
            $occurrence->setOccurrenceCreatedAt(new \DateTime())->setSpecies($singleSpecies);
            //getUser shortcut... see https://symfony.com/blog/new-in-symfony-3-2-user-value-resolver-for-controllers
            $user = $this ->getUser();
            $occurrence->setInputter($user);
            }
            $objectManager->persist($occurrence);
            $objectManager->flush();
            $this->addFlash('success', 'Your changes were saved');
//            return $this->redirectToRoute('occurrences_list', ['id'=> $singleSpecies->getId()]);
            return $this->redirectToRoute('occurrences_list', ['wormsAphiaId'=> $occurrence->getSpecies()->getWormsAphiaId()]);
            ;
        }

        return $this->render('species_occurrences/occurrence_create_edit.html.twig',[
            'formOccurrence'=> $form->createView(),
            'singleSpecies' => $singleSpecies,
            'occurrence'=> $occurrence,
            'editMode'=>$occurrence->getId()!==null,
            'editModeSpecies'=>$flagEditSpecies

        ]);
    }



}
