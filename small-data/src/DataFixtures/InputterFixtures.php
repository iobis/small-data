<?php

namespace App\DataFixtures;

use App\Entity\Inputter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class InputterFixtures extends Fixture
{
//https://symfony.com/doc/master/bundles/DoctrineFixturesBundle/index.html
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $arrayInputters = ["Mineur"=>"Frederic", "Provoost"=>"Pieter", "Bosch"=>"Samuel", "Appeltans"=>"Ward", "Oneill"=>"Ian"  ];

        foreach ($arrayInputters as $lastName => $firstName){
            $inputter = new Inputter();



            $inputter->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail(strtolower($firstName[0].".".$lastName)."@smalldata.com")
                ->setUsername(strtolower($firstName[0].$lastName));

            $hash = $this->encoder->encodePassword($inputter,"smalldata");
            $inputter->setPassword($hash);



            $manager->persist($inputter);

        }
        $manager->flush();
    }
}
