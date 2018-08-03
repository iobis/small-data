<?php

namespace App\Controller;

use App\Entity\Occurrence;
use App\Entity\Species;
use App\Form\OccurrenceType;
use App\Repository\OccurrenceRepository;
use App\Repository\SpeciesRepository;
use Doctrine\Common\Persistence\ObjectManager;
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
        $form = $this->createForm(OccurrenceType::class, $occurrence);
//        $form = $this->createFormBuilder($occurrence)>  add add ->add('associatedMediaUrl')->getForm();

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
     * @Route("occurrence/{id}/edit", name="occurrence_edit")
     */
    public function formOccurrenceEdit(Occurrence $occurrence, Request $request, ObjectManager $objectManager){


        $form = $this->createForm(OccurrenceType::class, $occurrence)
                // all the fields ((not including the following are in OccurrenceType
                    ->add('species', EntityType::class, [
                        'class'=>Species::class,
                        'choice_label'=> 'speciesNameWorms'
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $objectManager->persist($occurrence);
            $objectManager->flush();
            return $this->redirectToRoute('occurrences_list', ['id'=> $occurrence->getSpecies()->getId()]);
        }

        return $this->render('species_occurrences/occurrence_edit.html.twig',[
            'occurrence' => $occurrence,
            'formOccurrenceEdit'=> $form->createView()
        ]);

}


}
