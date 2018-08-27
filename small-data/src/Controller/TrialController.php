<?php

namespace App\Controller;

use App\Entity\Occurrence;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TrialController extends Controller
{
    /**
     * @Route("/trial", name="trial")
     */
    public function index(ObjectManager $manager)
    {


//       $occurrencesRep  = $this->getDoctrine()->getRepository(Occurrence::class);
//           $occurrences = $occurrencesRep->findAll();
//        $qb = $er->createQueryBuilder();
           $em = $this->getDoctrine()->getManager();
           $sql ="SELECT first_Name FROM inputter";
           $result = $em->getConnection()->prepare($sql);
           $result->execute();
           while ($row= $result->fetch()){
               print_r($row);
           }


        return $this->render('trial/index.html.twig', [
            'controller_name' => 'TrialController'

        ]);
    }
}
