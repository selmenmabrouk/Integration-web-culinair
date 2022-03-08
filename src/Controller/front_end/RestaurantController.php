<?php

namespace App\Controller\front_end;

use App\Entity\Restaurant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantController extends AbstractController
{
    /**
     * @Route("/affichRestaurant", name="affichRestaurant")
     */
    public function afficherRestaurant(): Response
    {
        return $this->render('front_end/Restaurant/AfficherRestaurant.html.twig', [
            "Restaurant" => $this->getDoctrine()->getRepository(Restaurant::class)->findAll(),
        ]);
    }

    /**
     * @Route("/map", name="map")
     */
    public function mewmap(): Response
    {
        return $this->render('front_end/restaurant/map.html.twig', [
            "restaurant" => $this->getDoctrine()->getRepository(Restaurant::class)->findAll(),
        ]);
    }
}
