<?php

namespace App\DataFixtures;

use App\Entity\Occurrence;
use App\Entity\Phylum;
use App\Entity\Species;
use App\Entity\Inputter;
use App\Repository\InputterRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\DateTime;


class OccurrencesSpeciesFixtures extends Fixture
{

    public const SPECIES_REFERENCE = 'species';

    //https://symfony.com/doc/master/bundles/DoctrineFixturesBundle/index.html
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
//
        //https://github.com/fzaninotto/Faker
        $faker = \Faker\Factory::create();
        $arrayInputters = ["Mineur"=>"Frederic", "Provoost"=>"Pieter", "Bosch"=>"Samuel", "Appeltans"=>"Ward", "Oneill"=>"Ian", "DeClerck" => "Olivier" ];

        foreach ($arrayInputters as $lastName => $firstName){
            $inputter = new Inputter();

            $inputter->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail(strtolower($firstName[0].".".$lastName)."@smalldata.com")
                ->setUsername(strtolower($firstName[0].$lastName));
            $inputter->setRoles(['ROLE_INPUTTER']);
            $hash = $this->encoder->encodePassword($inputter,"smalldata");
            $inputter->setPassword($hash);
            $manager->persist($inputter);
        }
        foreach ($arrayInputters as $lastName => $firstName){
            $inputter = new Inputter();
            $inputter->setFirstName($firstName)
                ->setLastName($lastName.'1')
                ->setEmail(strtolower($firstName[0].".".$lastName."1")."@smalldata.com")
                ->setUsername(strtolower($firstName[0].$lastName."1"));
            $inputter->setRoles(['ROLE_INPUTTER', 'ROLE_VALIDATOR']);

            $hash = $this->encoder->encodePassword($inputter,"smalldata");
            $inputter->setPassword($hash);
            $manager->persist($inputter);

        }
        foreach ($arrayInputters as $lastName => $firstName){
            $inputter = new Inputter();

            $inputter->setFirstName($firstName)
                ->setLastName($lastName.'2')
                ->setEmail(strtolower($firstName[0].".".$lastName.'2')."@smalldata.com")
                ->setUsername(strtolower($firstName[0].$lastName.'2'));
            $inputter->setRoles(['ROLE_INPUTTER', 'ROLE_VALIDATOR', 'ROLE_ADMINISTRATOR']);
            $hash = $this->encoder->encodePassword($inputter,"smalldata");
            $inputter->setPassword($hash);
            $manager->persist($inputter);
        }

        $manager->flush();

        $csvPhyla = fopen(dirname(__FILE__).'/phyla.csv', 'r');
        $rowCsvPhyla = 0;
        while (!feof($csvPhyla)){
            $rowCsvPhyla++;
            $linePhyla = fgetcsv($csvPhyla, [], ';');
            if($rowCsvPhyla>1){
                $phylum = new Phylum();
                $phylum->setPhylumNameWorms($linePhyla[0]);
                $manager->persist($phylum);
            }
        }
        fclose($csvPhyla);
        $manager->flush();


        $phylaRep = $manager->getRepository(Phylum::class);
        $phylaForSpecies = $phylaRep->findBy([]);

//https://stackoverflow.com/questions/35792244/symfony-doctrine-data-fixture-how-to-handle-large-csv-file
        $csvSpecies = fopen(dirname(__FILE__).'/species.csv', 'r');
        $rowCsvSpecies=0;
//        $species= array();
        while (!feof($csvSpecies)){
            $rowCsvSpecies++;

            $lineSpecies = fgetcsv($csvSpecies, [], ';');
            if($rowCsvSpecies>1) {
                $species = new Species();
                $species->setSpeciesNameWorms($lineSpecies[0] );
                $species->setWormsAphiaId($lineSpecies[1]);
                foreach ($phylaForSpecies as $phylumForSpecies) {
                    if($phylumForSpecies->getPhylumNameWorms()== $lineSpecies[2]){
                        $species->setPhylum($phylumForSpecies);
                    }

                }

                $manager->persist($species);

//                $this->addReference('species-'.$rowCsvSpecies, $species[$rowCsvSpecies]);
                ////!!! the first element of the array is NULL

            }
        }
        fclose($csvSpecies);
        $manager->flush();




        $speciesRep = $manager->getRepository(Species::class);
        $speciesForOccurrences = $speciesRep->findBy([]);
        $inputterRep=$manager->getRepository(Inputter::class);
        $inputtersForOccurrences = $inputterRep->findBy([]);


        $csvOccurrences = fopen(dirname(__FILE__).'/occurrences.csv', 'r');
        $rowCsvOccurrences = 0;
        while (!feof($csvOccurrences)) {
            $rowCsvOccurrences++;
            $lineOccurrence = fgetcsv($csvOccurrences, [], ';');
            if ($rowCsvOccurrences > 1){
                $occurrence = new Occurrence();
            foreach ($speciesForOccurrences as $singleSpeciesForOccurrences) {
                if ($singleSpeciesForOccurrences->getSpeciesNameWorms() == $lineOccurrence[1]) {
                    $occurrence->setSpecies($singleSpeciesForOccurrences);
                }
            }
//                $species = $speciesForOccurrences[array_rand($speciesForOccurrences)];
//                $occurrence->setSpecies($species);


                    $occurrence->setDecimalLatitude($lineOccurrence[6]);
                    $occurrence->setDecimalLongitude($lineOccurrence[5]);
                    //https://stackoverflow.com/questions/12447110/php-date-format-remove-time-and-more
                  $date = ($lineOccurrence[4].'-01-01');
//                $date = ($lineOccurrence[4]);
                    $createDate = new \DateTime($date);
//                    $dateWithoutTime = $createDate->format('YYYY-MM-DD');
//                    $occurrence->setEventDate($dateWithoutTime);
//                https://stackoverflow.com/questions/6238992/converting-string-to-date-and-datetime
//                        $year = $lineOccurrence[4];
//                    $dateString = $year.'-01-01';
//                    $ymd = \DateTime::createFromFormat('Y-m-d', $dateString);
//                    $occurrence->setEventDate($ymd);

                 //  $occurrence->setEventDate($faker->dateTimeBetween('-100 years'));


                    $occurrence->setEventDate($createDate);
                   $occurrence->setLocality($lineOccurrence[3]);
                   $occurrence->setOccurrenceRemarks($lineOccurrence[2]);

                    //https://stackoverflow.com/questions/25278645/getting-a-random-object-from-an-array-in-php
                    $inputter = $inputtersForOccurrences[array_rand($inputtersForOccurrences)];
                    $timeCreationAndLastModification = $faker->dateTimeBetween('-100 days');
                    $occurrence->setInputter($inputter);
                    $occurrence->setOccurrenceCreatedAt($timeCreationAndLastModification);
                    $occurrence->setLastModifier($inputter);
                    $occurrence->setLastModifiedAt($timeCreationAndLastModification);

                    $manager->persist($occurrence);




        }

        }
        fclose($csvOccurrences);
        $manager->flush();


    }
}
