<?php

namespace App\Controller;

use App\Controller\SmallDataController;
use App\Entity\Occurrence;
use App\Entity\Species;
use App\Entity\Phylum;
use App\Form\OccurrenceCreateType;
use App\Form\OccurrenceEditType;
use App\Form\OccurrenceType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FormsController extends Controller
{

    /**
     * @Route("/{idSpecies}/create_occurrence", name="occurrence_create")
     * @Security("has_role('ROLE_INPUTTER')")
     */
    public function formCreateOccurrence($idSpecies, Request $request, ObjectManager $objectManager){
        $phyla = $objectManager->getRepository(Phylum::class)->findBy([],[]);
        $singleSpecies = $this->getDoctrine()->getRepository(Species::class)
            ->findOneBy(['id'=>$idSpecies]);

        $occurrence = new Occurrence();

        $form = $this->createForm(OccurrenceType::class, $occurrence);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //for other fields added see OccurrenceCreatetype class

            $occurrence->setOccurrenceCreatedAt(new \DateTime());
            $occurrence->setSpecies($singleSpecies);
            //getUser shortcut... see https://symfony.com/blog/new-in-symfony-3-2-user-value-resolver-for-controllers
            $user = $this->getUser();
            $occurrence->setInputter($user);
            $occurrence->setLastModifiedAt(new \DateTime());
            $occurrence->setLastModifier($user);

            $objectManager->persist($occurrence);
            $objectManager->flush();

            return $this->redirectToRoute('occurrence_details',
                ['wormsAphiaId'=>$occurrence->getSpecies()->getWormsAphiaId(),
                    'idOccurrence'=>$occurrence->getId(),
                    'phyla'=>$phyla
                ]);
        }

        return $this->render('forms/create_occurrence.html.twig', [
            'formCreateOccurrence' =>$form->createView(),
            'singleSpecies'=> $singleSpecies,
            'occurrence'=>$occurrence,
            'phyla'=>$phyla

        ]);

    }


/*CHECK FOR STANDARD FORM EDIT GOS*/
//https://stackoverflow.com/questions/23569972/symfony-twig-forms-submitting-through-html-form-action-path
    /**
     *@Route("/occurrence/{idOccurrence}/editFields", name="occurrence_edit")
     *@Security("has_role('ROLE_INPUTTER')")
     */
    public function formEditOccurrence($idOccurrence , Request $request, ObjectManager $objectManager){
//        $singleSpecies = $this->getDoctrine()->getRepository(Species::class)
//            ->findOneBy(['wormsAphiaId'=>$wormsAphiaId]);
        $phyla = $objectManager->getRepository(Phylum::class)->findBy([],[]);


        $occurrence = $this->getDoctrine()->getRepository(Occurrence::class)
            ->findOneBy(['id'=>$idOccurrence]);

        $species = $occurrence->getSpecies();
        $occurrences = $species->getOccurrences();
        $intervalsWithFreqAndOccurrence = $this->container->get('App\Controller\SmallDataController')->renderOccurrenceIntervals($species, $occurrences, $objectManager);
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


        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user = $this->getUser();  //getUser shortcut... see https://symfony.com/blog/new-in-symfony-3-2-user-value-resolver-for-controllers
            $occurrence->setLastModifiedAt(new \DateTime());
            $occurrence->setLastModifier($user);

            $objectManager->persist($occurrence);
            $objectManager->flush();

            return $this->redirectToRoute('occurrence_details',
                ['wormsAphiaId'=>$occurrence->getSpecies()->getWormsAphiaId(),
                    'idSpecies'=>$occurrence->getSpecies()->getId(),
                    'idOccurrence'=>$occurrence->getId(),
                    'phyla'=>$phyla
                ]);
        }

        return $this->render('forms/edit_occurrence.html.twig', [
            'formEditOccurrence'=>$form->createView(),
            'singleSpecies'=>$occurrence->getSpecies(),
            'occurrence'=>$occurrence,
            'intervalsWithFreqAndOccurrences'=> $intervalsWithFreqAndOccurrence,
            'phyla'=>$phyla
        ]);
    }

//    public function addSpecies(Request $request){
//      See AdminController
//    }






    /**
     * @Route("/occurrence/{idOccurrence}/editGPS", name="occurrence_edit_gps")
     * @Security("has_role('ROLE_INPUTTER')")
     */
    public function formEditOccurrenceGPS (Request $request, ObjectManager $manager, $idOccurrence){
//        https://stackoverflow.com/questions/23569972/symfony-twig-forms-submitting-through-html-form-action-path
//        https://symfony.com/doc/current/introduction/http_fundamentals.html

        $occurrence = $manager->getRepository(Occurrence::class)->findOneBy(['id'=>$idOccurrence]);
        $phyla = $manager->getRepository(Phylum::class)->findBy([],[]);

        if(isset($_POST['update_gps'])){
//            $latitude= $request->query->get($_POST['latitude_gps']);
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
            return $this->redirectToRoute('occurrence_details',
                ['wormsAphiaId'=>$occurrence->getSpecies()->getWormsAphiaId(),
                    'idOccurrence'=>$occurrence->getId(),
                    'phyla'=>$phyla

                ]);
        }


        return $this->render ('forms/edit_occurrence_GPS.html.twig', [
            'occurrence'=>$occurrence,
            'singleSpecies'=>$occurrence->getSpecies(),
            'phyla'=>$phyla
        ]);

    }


}
