<?php

namespace App\Controller\front_end;

use App\Entity\Restaurant;
use App\Form\RestaurantType;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Snipe\BanBuilder\CensorWords;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
