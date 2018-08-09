<?php

namespace App\Controller;

use App\Entity\Inputter;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller
{
    /**
     * @Route("/registration", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $inputter = new Inputter();

        $form = $this->createForm(RegistrationType::class, $inputter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($inputter, $inputter->getPassword());
            $inputter->setPassword($hash);

            $manager->persist($inputter);
            $manager->flush();
           return $this->redirectToRoute('security_login');

        }

        return $this->render('security/registration.html.twig', [
//            'controller_name' => 'SecurityController',
            'formRegistration'=>$form->createView()
        ]);
    }

    /**
     * @Route ("/login", name="security_login")
     */
    public function login(){
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/logout", name= "security_logout")
     */
    public function logout (){
        //The route for logout is in the security.yaml file

    }

    /**
     * @Route("/security", name="security_index")
     */
    public function index(){
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController'
        ]);
    }
}
