<?php

namespace App\DataFixtures;

use App\Entity\Occurrence;
use App\Entity\Species;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class OccurrencesSpeciesFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
//
        //https://github.com/fzaninotto/Faker
        $faker = \Faker\Factory::create();
        //associative array of species and AphiaId
        $arraySpecies = array("494791"=>"Sargassum muticum", "145721"=>"Undaria pinnatifida","137206"=>"Chelonia mydas","123776"=>"Asterias rubens","135304"=>"Chrysaora hysoscella");

        foreach($arraySpecies as $aphiaId => $speciesName) {  //aphiaId is key and speciesName is value
            $species = new Species();
            $species->setWormsAphiaId($aphiaId)
                ->setSpeciesNameWorms($speciesName);
            $manager->persist($species);

            for($i = 0; $i<= mt_rand (0, 30); $i++){
                $occurrence = new Occurrence();
                $occurrence->setEventDate($faker->dateTimeBetween('-100 years'))
                    ->setDecimalLongitude($faker->longitude)
                    ->setDecimalLatitude($faker->latitude)
                    ->setOccurrenceCreatedAt($faker->dateTimeBetween('-100 days'))
                    ->setSpecies($species)
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
