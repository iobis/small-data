<?php

namespace App\Controller;

use App\Entity\Occurrence;
use App\Entity\Phylum;
use App\Entity\Species;
use App\Repository\PhylumRepository;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ValidationController extends Controller
{
    /**
     * @Route("/validation/{idPhylum}/{wormsAphiaId}/{mode}", name="non_valid_list_for_phylum_with_details_one_species")
     * @Route("/validation/{idPhylum}", name="non_valid_list_for_phylum")
     */
    public function showNonValid(ObjectManager $manager, $idPhylum = null, $wormsAphiaId = null, $mode = null)
    {
        if($mode = 'show_list'){
            $singleSpeciesToDisplay = $manager->getRepository(Species::class)->findBy(['wormsAphiaId'=>$wormsAphiaId]);

        }
        $phylumToDisplay = $manager->getRepository(Phylum::class)->findOneBy(['id'=>$idPhylum]);
        $occurrencesNonValid = $manager->getRepository(Occurrence::class)->findBy(['isValidated'=>false]);

        return $this->render('validation/nonvalidoccurrences.html.twig', [
            'controller_name' => 'ValidationController',
            'phylumToDisplay'=>$phylumToDisplay,
            'occurrencesNonValid'=>$occurrencesNonValid,
            'singleSpeciesToDisplay'=>$singleSpeciesToDisplay,
            'mode'=>$mode,
            'wormsAphiaIdForDetails' => $wormsAphiaId
        ]);
    }
}
