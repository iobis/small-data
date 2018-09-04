<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminUsersController extends Controller
{
    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function index()
    {
        return $this->render('admin_users/index.html.twig', [
            'controller_name' => 'AdminUsersController',
        ]);
    }
}
