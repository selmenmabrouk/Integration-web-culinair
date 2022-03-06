<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(): Response
    
    {
        //$user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('base.html.twig');
    }
}
