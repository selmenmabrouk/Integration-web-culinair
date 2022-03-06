<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class StatsUsersController extends AbstractController
{
    /**
     * @Route("/statsUsers", name="statsUsers")
     */
    public function index(): Response
    {


        $Users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $users=0;
        $admins=0;
        foreach ($Users as $User){
            if($User->getRoles()[0]=='ROLE_USER')
            {$users++;

            }
            else if ($User->getRoles()[0]=='ROLE_ADMIN')
            {
            $admins++;
            }


        }












        return $this->render('admin/stats_users/index.html.twig', [
            'controller_name' => 'StatsUsersController',
            'users'=>$users,'admins'=>$admins
        ]);
    }
}
