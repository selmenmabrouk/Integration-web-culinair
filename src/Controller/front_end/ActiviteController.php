<?php

namespace App\Controller\front_end;

use App\Repository\ActiviteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/activite")
 */
class ActiviteController extends AbstractController
{
    /**
     * @Route("/now", name="activite_front", methods={"GET"})
     */
    public function activite_front(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('front_end/activite/index.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);
    }

}
