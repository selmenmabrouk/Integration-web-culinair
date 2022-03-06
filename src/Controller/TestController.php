<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{

    /**
     * @Route("/test", name="test")
     */
    public function index(): Response
    {
        return $this->render('base-back.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
