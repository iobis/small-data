<?php

namespace App\Controller;

use App\Entity\Inputter;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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


    //https://symfony.com/doc/current/security/form_login_setup.html
    /**
     * @Route ("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils){

        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('security/login.html.twig', [
            'error'=>$error
        ]);
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


    /**
     * @Route("/security/changePassword/{idInputter}/", name="change_password")
     */
    public function changePassword(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, $idInputter){

        $inputter = $manager->getRepository(Inputter::class)->findOneBy(['id'=>$idInputter]);

        $form = $this->createForm(RegistrationType::class, $inputter);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($inputter, $inputter->getPassword());
            $inputter->setPassword($hash);

            $manager->persist($inputter);
            $manager->flush();
            return $this->redirectToRoute('security_login');
        }

        return $this->render ('security/change_password.html.twig', [
            'formChangePassword'=>$form->createView()
            ]);
    }

}
