<?php

namespace App\Controller;

use App\Entity\Occurrence;
use App\Entity\Species;
use App\Entity\SpeciesImage;
use App\Form\SpeciesImageType;
use App\Form\SpeciesType;
use Doctrine\Common\Persistence\ObjectManager;
use Proxies\__CG__\App\Entity\Phylum;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AdministrationController extends Controller
{
    /**
     * @Route("/admin_species", name="admin_species")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
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
     * @Security("has_role('ROLE_ADMINISTRATOR')")
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
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     */
    public function removeSingleSpecies(ObjectManager $manager, $idSpecies)
    {
        $singleSpecies = $manager->getRepository(Species::class)->findOneBy(['id'=>$idSpecies]);
        $occurrencesForSingleSpecies = $manager->getRepository(Occurrence::class)->findBy(['species'=>$singleSpecies]);

        //https://stackoverflow.com/questions/42906587/delete-files-from-a-folder-in-symfony-3
        $imagesSpecies = $singleSpecies->getSpeciesImages();
        foreach ($imagesSpecies as $image){
            $imageName=$image->getSpeciesImageName();
            $fileSystem = new Filesystem();
            $fileSystem->remove($this->get('kernel')->getRootDir().'/../public/uploads/images_species/'.$imageName);
            $manager->remove($image);
        }
//        $imageSpeciesFile = $singleSpecies->getImageSpecies();
//        $fileSystem = new Filesystem();
//        $fileSystem->remove($this->get('kernel')->getRootDir().'/../public/uploads/images_species/'.$imageSpeciesFile);

        $manager->remove($singleSpecies);
        foreach ($occurrencesForSingleSpecies as  $occurrence){
            $manager->remove($occurrence);
        }
        $manager->flush();
        $this->addFlash(
            'notice_remove_species',
            'The species has been removed from the system'
        );
        return $this->redirectToRoute('admin_species');
    }



    /**
     * @Route("admin_species/create_csv_occurrences{idSpecies}", name = "csv_species_occurrences")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     */
    public function createCsvOccurrences (ObjectManager $manager, $idSpecies)
    {

        //https://knpuniversity.com/screencast/symfony2-ep3/csv-download
        //https://vauly.com/symfony2-export-csv

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

    /**
     * @Route ("admin_species/add", name="add_species")
     * @Route ("admin_species/{idSpecies}", name="edit_species")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     */
    public function addEditSpecies (Request $request, ObjectManager $manager, $idSpecies = null)
    {

            if ($idSpecies){
                $singleSpecies = $manager->getRepository(Species::class)->findOneBy(['id'=>$idSpecies]);
                $mode = 'edit';
            } else {
                $singleSpecies = new Species();
                $mode = 'add';
            }



            $form = $this->createForm(SpeciesType::class, $singleSpecies);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){

                $manager->persist($singleSpecies);
                $manager->flush();
             return $this->redirectToRoute('admin_species',[
                ]);
            }
            return $this->render('administration/add_edit_species.html.twig',[
                'formAddEditSpecies'=>$form->createView(),
                    'mode'=>$mode
                ]
                );

    }

    /**
     * @Route ("admin_species/image_manager/{idSpecies}", name = "manage_images_species")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     */

    public function addSpeciesImage (Request $request, ObjectManager $manager, $idSpecies)
    {
        $singleSpecies = $manager->getRepository(Species::class)->findOneBy(['id'=>$idSpecies]);

        $speciesImage = new SpeciesImage();

        $form= $this->createForm(SpeciesImageType::class, $speciesImage);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('speciesImageName')->getData();
        $speciesName = str_replace(' ', '_',$singleSpecies->getSpeciesNameWorms());
        $fileName = $speciesName.'_'.$this->generateUniqueFileName().'.'.$file->guessExtension();
        $file->move(
            $this->getParameter('images_species_directory'),
            $fileName
        );
       $speciesImage->setSpeciesImageName($fileName);
       $speciesImage->setSpecies($singleSpecies);

        $manager->persist($speciesImage);
        $manager->flush();
        return $this->redirectToRoute('manage_images_species', [
            'idSpecies'=>$idSpecies
        ]);
        }




        return $this->render('administration/image_species.html.twig', [
            'formImageSpecies'=>$form->createView(),
            'singleSpecies'=>$singleSpecies

        ]);

    }

    /**
     * @Route ("admin_species/Image/{idImageSpecies}/{display}", name = "editForDisplay_image_species")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     */
    public function editDisplayImageSpecies (ObjectManager $manager, $idImageSpecies, $display){
        $speciesImageToEdit = $manager->getRepository(SpeciesImage::class)->findOneBy(['id'=>$idImageSpecies]);
        $idSpecies= $speciesImageToEdit->getSpecies()->getId();
        if ($display == 'yes'){
            $speciesImageToEdit->setIsForDisplay(true);
        } else {
            $speciesImageToEdit->setIsForDisplay(false);
        }
        $manager->persist($speciesImageToEdit);
        $manager->flush();

        return $this->redirectToRoute('manage_images_species', [
            'idSpecies'=>$idSpecies
            ]
            );
    }


    /**
     * @Route("admin_species/Image/Main/{idImageSpecies}/{main}/", name="editmain_image_species")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     */
    public function editMainStatusImage (ObjectManager $manager, $idImageSpecies, $main){
        $speciesImageToEdit = $manager->getRepository(SpeciesImage::class)->findOneBy(['id'=>$idImageSpecies]);
        $idSpecies = $speciesImageToEdit->getSpecies()->getId();
        if($main == 'yes'){
            $speciesImageToEdit->setIsMain(true);
        } elseif ($main == 'no') {
            $speciesImageToEdit->setIsMain(false);
        }
        $manager->persist($speciesImageToEdit);
        $manager->flush();
        return $this->redirectToRoute('manage_images_species', [
            'idSpecies'=>$idSpecies
        ]);



    }

    /**
     * @Route("admin_species/image_remove/{idImageSpecies}", name="remove_image_species")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     */
    public function removeImageSpecies (ObjectManager $manager, $idImageSpecies){
        $speciesImage = $manager->getRepository(SpeciesImage::class)->findOneBy(['id'=>$idImageSpecies]);
        dump($speciesImage);
        $idSpecies = $speciesImage->getSpecies()->getId();

        $imageSpeciesFile = $speciesImage->getSpeciesImageName();
        $fileSystem = new Filesystem();
        $fileSystem->remove($this->get('kernel')->getRootDir().'/../public/uploads/images_species/'.$imageSpeciesFile);

        $manager->remove($speciesImage);
        $manager->flush();
        return $this->redirectToRoute('manage_images_species', [
            'idSpecies'=>$idSpecies
        ]);


    }






    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

}

//https://stackoverflow.com/questions/49604601/call-to-a-member-function-guessextension-on-string
//                $file = $singleSpecies->getImageSpecies();
//if($singleSpecies->getImageSpecies()){
//
//
//    $file = $form->get('imageSpecies')->getData();
//    $speciesName = str_replace(' ', '_',$singleSpecies->getSpeciesNameWorms());
//    $fileName = $speciesName.'_'.$this->generateUniqueFileName().'.'.$file->guessExtension();
//
//    $file->move(
//        $this->getParameter('images_species_directory'),
//        $fileName
//    );
//
//    $singleSpecies->setImageSpecies($fileName);
//
//}


//EDIT IMAGE

//$singleSpecies->setImageSpecies(
//    new File($this->getParameter('images_species_directory').'/'.$singleSpecies->getImageSpecies())
//);





