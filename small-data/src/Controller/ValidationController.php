<?php

namespace App\Controller;

use App\Entity\Occurrence;
use App\Entity\Phylum;
use App\Entity\Species;
use App\Form\OccurrenceType;
use App\Repository\PhylumRepository;
use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ValidationController extends Controller
{
    /**
     * @Route("/validation/{idPhylum}/{idSpecies}/{mode}", name="non_valid_list_for_phylum_with_details_one_species")
     * @Route("/validation/{idPhylum}", name="non_valid_list_for_phylum")
     * @Security("has_role('ROLE_VALIDATOR')")
     */
    public function showNonValid(ObjectManager $manager, $idPhylum = null, $idSpecies = null, $mode = null)
    {
        $phyla = $manager->getRepository(Phylum::class)->findBy([],[]);

        if($mode = 'show_list'){
            $singleSpeciesToDisplay = $manager->getRepository(Species::class)->findBy(['id'=>$idSpecies]);

        }
        $phylumToDisplay = $manager->getRepository(Phylum::class)->findOneBy(['id'=>$idPhylum]);
        $occurrencesNonValid = $manager->getRepository(Occurrence::class)->findBy(['isValidated'=>false]);

        return $this->render('validation/nonvalidoccurrences.html.twig', [
            'controller_name' => 'ValidationController',
            'phylumToDisplay'=>$phylumToDisplay,
            'occurrencesNonValid'=>$occurrencesNonValid,
            'singleSpeciesToDisplay'=>$singleSpeciesToDisplay,
            'mode'=>$mode,
            'idSpeciesForDetails' => $idSpecies,
            'phyla'=>$phyla
        ]);
    }


    //!!!! WEIRD problems happens when "{idOccurrence}" is not followed by a slash bar or not at the end of the route.....
    /**
     * @Route("/validation/validate/{idOccurrence}/", name="validate_occurrence")
     * @Security("has_role('ROLE_VALIDATOR')")
     */
    public function validateOccurrence(ObjectManager $manager, $idOccurrence=null)
    {
        $phyla = $manager->getRepository(Phylum::class)->findBy([],[]);
        $occurrence = $manager->getRepository(Occurrence::class)->findOneBy(['id' => $idOccurrence]);
        $wormsAphiaId = $occurrence->getSpecies()->getWormsAphiaId();
        $idPhylum = $occurrence->getSpecies()->getPhylum()->getId();
        $idSpecies = $occurrence->getSpecies()->getId();
        $occurrence->setIsValidated(true);
        $manager->persist($occurrence);
        $manager->flush();

        return $this->redirectToRoute('non_valid_list_for_phylum_with_details_one_species', [
                'idPhylum'=>$idPhylum,
                'wormsAphiaId'=>$wormsAphiaId,
                'idSpecies'=>$idSpecies,
                'mode'=>'show_list',
                'phyla'=>$phyla
        ]);

    }



/**
 *@Route("/validation/editFields/{idOccurrence}/", name="validation_occurrence_edit")
 *@Security("has_role('ROLE_VALIDATOR')")
 */
public function formEditOccurrenceValidation($idOccurrence , Request $request, ObjectManager $objectManager){
//        $singleSpecies = $this->getDoctrine()->getRepository(Species::class)
//            ->findOneBy(['wormsAphiaId'=>$wormsAphiaId]);
    $phyla = $objectManager->getRepository(Phylum::class)->findBy([],[]);


    $occurrence = $this->getDoctrine()->getRepository(Occurrence::class)
        ->findOneBy(['id'=>$idOccurrence]);

    $species = $occurrence->getSpecies();
    $occurrences = $species->getOccurrences();


    if ($occurrences) {
        $intervalsWithFreqAndOccurrence = $this->container->get('App\Controller\SmallDataController')->renderOccurrenceIntervals($species, $occurrences, $objectManager);

    } else {
        $intervalsWithFreqAndOccurrence = null;
    }



    dump($intervalsWithFreqAndOccurrence);

    $form = $this->createForm(OccurrenceType::class, $occurrence);
    $form ->add('species', EntityType::class, [
        'class'=>Species::class,
        'query_builder' =>function (EntityRepository $er){ //https://stackoverflow.com/questions/8164682/doctrine-and-like-query
//https://stackoverflow.com/questions/37326605/symfony-and-doctrine-dql-query-builder-how-to-use-multiple-setparameters-inside/37326606
            return $er->createQueryBuilder('sp')
                ->select('sp')
                ->where('sp.speciesNameWorms LIKE :species')
                // ->setParameter('species', 'C%')
                ->setParameter('species', '%')
                ->orderBy('sp.speciesNameWorms', 'ASC');
        },
        'choice_label'=> 'speciesNameWorms'
    ]);
//
//
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){

        $user = $this->getUser();  //getUser shortcut... see https://symfony.com/blog/new-in-symfony-3-2-user-value-resolver-for-controllers
        $occurrence->setLastModifiedAt(new \DateTime());
        $occurrence->setLastModifier($user);

        $objectManager->persist($occurrence);
        $objectManager->flush();

        return $this->redirectToRoute('non_valid_list_for_phylum_with_details_one_species',
            ['wormsAphiaId'=>$occurrence->getSpecies()->getWormsAphiaId(),
                'idOccurrence'=>$occurrence->getId(),
                'idSpecies'=>$occurrence->getSpecies()->getId(),
                'phyla'=>$phyla,
                'idPhylum'=>$occurrence->getSpecies()->getPhylum()->getId(),
            ]);
    }

    return $this->render('validation/edit_occurrence_for_validation.html.twig', [
        'formEditOccurrence'=>$form->createView(),
        'singleSpecies'=>$occurrence->getSpecies(),
        'occurrence'=>$occurrence,
        'intervalsWithFreqAndOccurrences'=> $intervalsWithFreqAndOccurrence,
        'phyla'=>$phyla
    ]);
}

    /**
     * @Route("/validation/editGPS/{idOccurrence}/", name="validation_occurrence_edit_gps")
     * @Security("has_role('ROLE_VALIDATOR')")
     */
    public function formEditValidationOccurrenceGPS (Request $request, ObjectManager $manager, $idOccurrence){
//        https://stackoverflow.com/questions/23569972/symfony-twig-forms-submitting-through-html-form-action-path
//        https://symfony.com/doc/current/introduction/http_fundamentals.html
        $occurrence = $manager->getRepository(Occurrence::class)->findOneBy(['id'=>$idOccurrence]);
        $species = $occurrence->getSpecies();
        $occurrences = $species->getOccurrences();
//
        $phyla = $manager->getRepository(Phylum::class)->findBy([],[]);
        $intervalsWithFreqAndOccurrence = $this->container->get('App\Controller\SmallDataController')->renderOccurrenceIntervals($species, $occurrences, $manager);


        if(isset($_POST['update_gps'])){

            $latitude= $_POST['latitude_gps'];
            dump($latitude);
//            $longitude= $request->query->get($_POST['longitude_gps']);
            $longitude= $_POST['longitude_gps'];
            $occurrence->setDecimalLatitude($latitude);
            $occurrence->setDecimalLongitude($longitude);

            $user = $this->getUser();  //getUser shortcut... see https://symfony.com/blog/new-in-symfony-3-2-user-value-resolver-for-controllers
            $occurrence->setLastModifiedAt(new \DateTime());
            $occurrence->setLastModifier($user);

            $manager->persist($occurrence);
            $manager->flush();
            return $this->redirectToRoute('validation_occurrence_edit',
                ['wormsAphiaId'=>$occurrence->getSpecies()->getWormsAphiaId(),
                    'idOccurrence'=>$occurrence->getId(),
                    'phyla'=>$phyla,
                    'idPhylum'=> $occurrence->getSpecies()->getPhylum()->getId(),

                ]);
        }


        return $this->render ('validation/validation_edit_occurrence_GPS.html.twig', [
            'occurrence'=>$occurrence,
            'singleSpecies'=>$occurrence->getSpecies(),
            'phyla'=>$phyla,
            'intervalsWithFreqAndOccurrences'=> $intervalsWithFreqAndOccurrence
        ]);

    }







}
