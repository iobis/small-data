<?php

namespace App\Controller;

use App\Entity\Occurrence;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Proxies\__CG__\App\Entity\Inputter;
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

          $inputters = $manager->getRepository(Inputter::class);
           $allInputters = $inputters->findBy([]);
           $inputtersValidators = [];


           foreach ($allInputters as $inputter){
               $roles= $inputter->getRoles();
               foreach ($roles as $role){
                   if((string)$role=='ROLE_VALIDATOR'){
                       $inputtersValidators[]=$inputter;
                   }
               }
           }
           dump($inputtersValidators);




//           foreach ($inputtersValidators as $inputtersValidator){
//               echo $inputtersValidator->get
//           }


        return $this->render('trial/index.html.twig', [
            'controller_name' => 'TrialController'

        ]);
    }
}
