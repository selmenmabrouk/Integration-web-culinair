<?php

namespace App\Controller\front_end;

use App\Entity\Transport;
use App\Form\TransportType;
use App\Repository\TransportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/transport")
 */
class TransportController extends AbstractController
{
    /**
     * @Route("/now", name="transport_front", methods={"GET"})
     */
    public function transport_front(TransportRepository $transportRepository): Response
    {
        return $this->render('front_end/transport/TransportFront.html.twig', [
            'transport' => $transportRepository->findAll(),
        ]);
    }
}
