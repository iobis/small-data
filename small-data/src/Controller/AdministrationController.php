<?php

namespace App\Controller;

use App\Entity\Occurrence;
use App\Entity\Species;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;
use Proxies\__CG__\App\Entity\Phylum;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdministrationController extends Controller
{
    /**
     * @Route("/admin_species", name="admin_species")
     */
    public function ManageSpecies(ObjectManager $manager)
    {
        $species = $manager->getRepository(Species::class)->findBy([],['speciesNameWorms'=>'ASC']);
        $phyla = $manager->getRepository(Phylum::class)->findBy([],['phylumNameWorms'=>'ASC']);
        $occurrences = $manager->getRepository(Occurrence::class)->findBy([]);
        $occurrencesNonValid = $manager->getRepository(Occurrence::class)->findBy(['isValidated'=>false]);

        return $this->render('administration/admin_species.html.twig', [
            'controller_name' => 'AdministrationController',
            'species'=>$species,
            'phyla'=>$phyla,
            'occurrences'=>$occurrences,
            'occurrencesNonValid'=>$occurrencesNonValid
        ]);
    }


    /**
     * @Route("/removeSpecies/{idSpecies}", name = "warning_remove_singleSpecies")
     */
    public function warningRemoveSingleSpecies(ObjectManager $manager, $idSpecies)
    {
        $singleSpecies = $manager->getRepository(Species::class)->findOneBy(['id'=>$idSpecies]);
        return $this->render('administration/remove_species.html.twig', [
            'singleSpecies'=>$singleSpecies
        ]);
    }

    /**
     * @Route("admin_species/remove_{idSpecies}", name="remove_singleSpecies")
     */
    public function removeSingleSpecies(ObjectManager $manager, $idSpecies)
    {
        $singleSpecies = $manager->getRepository(Species::class)->findOneBy(['id'=>$idSpecies]);
        $occurrencesForSingleSpecies = $manager->getRepository(Occurrence::class)->findBy(['species'=>$singleSpecies]);
        $manager->remove($singleSpecies);
        foreach ($occurrencesForSingleSpecies as  $occurrence){
            $manager->remove($occurrence);
        }

        $manager->flush();

        return $this->redirectToRoute('admin_species');

    }

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    /**
     * @Route("admin_species/create_csv_occurrences{idSpecies}", name = "csv_species_occurrences")
     */
    public function createCsvOccurrences (ObjectManager $manager, $idSpecies)
    {
        $singleSpecies = $manager->getRepository(Species::class)->findOneBy(['id'=>$idSpecies]);
        $speciesName = $singleSpecies->getSpeciesNameWorms();
        $speciesWormsAphiaId = $singleSpecies->getWormsAphiaId();
        $occurrences = $manager->getRepository(Occurrence::class)->findBy(['species'=>$singleSpecies]);
                $rows = [];
                $header = ['species', 'id', 'locality', 'date', 'lat', 'long', 'remarks', 'inputter'];
                $rows[] = implode(';', $header);
                foreach($occurrences as $occurrence){
                    $data = [
                        $occurrence->getSpecies()->getSpeciesNameWorms(),
                        $occurrence->getId(),
                        $occurrence->getLocality(),
                        $occurrence->getEventDate()->format('Y-m-d'),
                        $occurrence->getDecimalLatitude(),
                        $occurrence->getDecimalLongitude(),
                        $occurrence->getOccurrenceRemarks(),
                        $occurrence->getInputter()->getUsername()

                    ];
                    $rows[] = implode(';', $data);
                }

                $content = implode("\n", $rows);
                    $response = new Response($content);
//                    $response->headers->set('Content-Type', 'text/csv');
                    $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
                    $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');
                return $response;

    }
}







//https://knpuniversity.com/screencast/symfony2-ep3/csv-download
//https://vauly.com/symfony2-export-csv

