<?php

namespace App\Controller\front_end;

use App\Entity\Evenement;
use App\Entity\Guide;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/guide")
 */
class GuideController extends AbstractController
{
    /**
     * @Route("/", name="guide_front")
     */
    public function AfficherGuide(): Response
    {
        return $this->render('front_end/guide/index.html.twig', [
            'guides' => $this->getDoctrine()->getRepository(guide::class)->findAll(),
        ]);
    }
}
