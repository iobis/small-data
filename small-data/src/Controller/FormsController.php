<?php

namespace App\Controller;

use App\Entity\Occurrence;
use App\Entity\Species;
use App\Form\OccurrenceCreateType;
use App\Form\OccurrenceEditType;
use App\Form\OccurrenceType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FormsController extends Controller
{

    /**
     * @Route("/{wormsAphiaId}/create_occurrence", name="occurrence_create")
     */
    public function formCreateOccurrence($wormsAphiaId, Request $request, ObjectManager $objectManager){
        $singleSpecies = $this->getDoctrine()->getRepository(Species::class)
            ->findOneBy(['wormsAphiaId'=>$wormsAphiaId]);

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
                ['wormsAphiaId'=>$occurrence->getSpecies()->getWormsAphiaId(), 'idOccurrence'=>$occurrence->getId()]);
        }

        return $this->render('forms/create_occurrence.html.twig', [
            'formCreateOccurrence' =>$form->createView(),
            'singleSpecies'=> $singleSpecies,
            'occurrence'=>$occurrence

        ]);

    }

    /**
     *@Route("/{wormsAphiaId}/occurrence/{idOccurrence}/editFields", name="occurrence_edit")
     */
    public function formEditOccurrence($idOccurrence , Request $request, ObjectManager $objectManager){
//        $singleSpecies = $this->getDoctrine()->getRepository(Species::class)
//            ->findOneBy(['wormsAphiaId'=>$wormsAphiaId]);

        $occurrence = $this->getDoctrine()->getRepository(Occurrence::class)
            ->findOneBy(['id'=>$idOccurrence]);

        $speciesName = $occurrence->getSpecies()->getSpeciesNameWorms();
        $speciesPhylum = $occurrence->getSpecies()->getPhylum();


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
                ['wormsAphiaId'=>$occurrence->getSpecies()->getWormsAphiaId(), 'idOccurrence'=>$occurrence->getId()]);
        }

        return $this->render('forms/edit_occurrence.html.twig', [
            'formEditOccurrence'=>$form->createView(),
            'singleSpecies'=>$occurrence->getSpecies(),
            'occurrence'=>$occurrence
        ]);
    }




}
