<?php

namespace App\Controller;

use App\Entity\Inputter;
use App\Entity\Phylum;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminUsersController extends Controller
{
    /**
     * @Route("/admin/users", name="admin_users")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     */
    public function displayAllUsers(ObjectManager $manager)
    {
        $inputters = $manager->getRepository(Inputter::class)->findBy([],['lastName'=>'ASC']);
        $phyla = $manager->getRepository(Phylum::class)->findBy([], ['phylumNameWorms'=>'ASC']);

        return $this->render('admin_users/admin_users.html.twig', [
            'inputters'=>$inputters,
            'phyla'=>$phyla
        ]);
    }

    /**
     * @Route("admin/users_expertise/{idInputter}/", name="edit_users_expertise")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     */

    public function editExpertise (ObjectManager $manager, $idInputter)
    {
        $inputter = $manager->getRepository(Inputter::class)->findOneBy(['id'=>$idInputter]);
        $phyla = $manager->getRepository(Phylum::class)->findBy([], ['phylumNameWorms'=>'ASC']);
        $roles=$inputter->getRoles();
    dump($roles);
        return $this->render('admin_users/admin_users_expertise.html.twig',[
            'phyla'=>$phyla,
            'inputter'=>$inputter

        ]);
    }

    /**
     * @Route("admin/users_expertise/{idInputter}/{idPhylum}/{mode}/", name="edit_user_phylum")
     * @Security("has_role('ROLE_ADMINISTRATOR')")
     */
    public function addRemoveFieldOfExpertise (ObjectManager $manager, $idInputter, $idPhylum, $mode)
    {
        $inputter = $manager->getRepository(Inputter::class)->findOneBy(['id'=>$idInputter]);

        $phylum = $manager->getRepository(Phylum::class)->findOneBy(['id'=>$idPhylum]);
        dump($phylum);
        if ($mode == 'add'){
            $inputter->addPhylumOfExpertise($phylum);
            $manager->persist($inputter);
        } elseif ($mode=='remove'){
            $inputter->removePhylumOfExpertise($phylum);
            $manager->persist($inputter);
        }
        $manager->flush();
//        $roles = $inputter->getRoles();
        if($inputter->getPhylumOfExpertise()==null){
//            unset($roles);
//https://stackoverflow.com/questions/26316089/remove-an-element-from-json-array
            $inputter->setRoles(null);
            $inputter->setRoles(['ROLE_INPUTTER']);
        } else {
            $inputter->setRoles(['ROLE_INPUTTER', 'ROLE_VALIDATOR']);
        }
        $manager->persist($inputter);
        $manager->flush();


        return $this->redirectToRoute('edit_users_expertise', [
                'idInputter'=>$inputter->getId()
            ]);


    }


}
