<?php

namespace App\DataFixtures;

use App\Entity\Occurrence;
use App\Entity\Species;
use App\Entity\Inputter;
use App\Repository\InputterRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class OccurrencesSpeciesFixtures extends Fixture
{
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
        $arrayInputters = ["Mineur"=>"Frederic", "Provoost"=>"Pieter", "Bosch"=>"Samuel", "Appeltans"=>"Ward", "Oneill"=>"Ian"  ];

        foreach ($arrayInputters as $lastName => $firstName){
            $inputter = new Inputter();

            $inputter->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail(strtolower($firstName[0].".".$lastName)."@smalldata.com")
                ->setUsername(strtolower($firstName[0].$lastName));
            $inputter->setRoles(['ROLE_USER']);
            $hash = $this->encoder->encodePassword($inputter,"smalldata");
            $inputter->setPassword($hash);
            $manager->persist($inputter);
        }
        foreach ($arrayInputters as $lastName => $firstName){
            $inputter = new Inputter();
            $inputter->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail(strtolower($firstName[0].".".$lastName."1")."@smalldata.com")
                ->setUsername(strtolower($firstName[0].$lastName."1"));
            $inputter->setRoles(['ROLE_USER', 'ROLE_USER_PLUS']);

            $hash = $this->encoder->encodePassword($inputter,"smalldata");
            $inputter->setPassword($hash);
            $manager->persist($inputter);

        }

        $manager->flush();




        $inputterRep=$manager->getRepository(Inputter::class);
        $inputtersForOccurrences = $inputterRep->findBy([]);

        //associative array of species and AphiaId
        $arraySpecies = array("494791"=>"Sargassum muticum", "145721"=>"Undaria pinnatifida","137206"=>"Chelonia mydas","123776"=>"Asterias rubens","135304"=>"Chrysaora hysoscella");

        foreach($arraySpecies as $aphiaId => $speciesName) {  //aphiaId is key and speciesName is value
            $species = new Species();
            $species->setWormsAphiaId($aphiaId)
                ->setSpeciesNameWorms($speciesName);
            $manager->persist($species);

            for($i = 0; $i<= mt_rand (0, 30); $i++){
                $occurrence = new Occurrence();
                //https://stackoverflow.com/questions/25278645/getting-a-random-object-from-an-array-in-php
                $inputter = $inputtersForOccurrences[array_rand($inputtersForOccurrences)];

                $occurrence->setEventDate($faker->dateTimeBetween('-100 years'))
                    ->setDecimalLongitude($faker->longitude)
                    ->setDecimalLatitude($faker->latitude)
                    ->setOccurrenceCreatedAt($faker->dateTimeBetween('-100 days'))
                    ->setSpecies($species)
                    ->setInputter($inputter)
                //optional
                    ->setLocality($faker->optional(0.9)->country)
                    ->setOccurrenceRemarks($faker->optional(0.7)->paragraph(2))
                    ->setAssociatedMediaUrl($faker->optional(0.3)->imageUrl(320,240));

                $manager->persist($occurrence);
            }
        }
       $manager->flush();
     }
}
