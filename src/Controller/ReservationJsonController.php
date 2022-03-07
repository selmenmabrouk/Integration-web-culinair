<?php

namespace App\Controller;

use App\Entity\Reservation;
//use App\Form\ReservationType;
use JsonSerializable;
use Normalizer as GlobalNormalizer;
use Serializable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Polyfill\Intl\Normalizer\Normalizer;

class ReservationJsonController extends AbstractController
{
    /**
     * @Route("/reservation/json", name="reservation_json")
     */
    public function index(): Response
    {
        return $this->render('reservation_json/index.html.twig', [
            'controller_name' => 'ReservationJsonController',
        ]);
    }

    /**
     * @Route("/reservation/{id}",name="Resid")
     */
    public function Resid(Request $request, $id, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository(Reservation::class)->find($id);
        $jsonContent = $Normalizer->normalize($reservation, 'json', ['groups' => 'post:read']);
        return $this->render('reservation_json/index.html.twig', ['data' => $jsonContent,]);
        return new Response(json_encode($jsonContent));
    }

    /**

     * @Route("allReservationJSON", name="all_Reservation")
     */
    public function liste(NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Reservation::class);
        $reservation = $repository->findAll();
        $jsonContent = $Normalizer->normalize($reservation, 'json', ['groups' => 'post:read']);
        return $this->render('reservation\allReservationJSON.html.twig', ['data' => $jsonContent,]);
        return new Response(json_encode($jsonContent));
    }



    /**
     *
     * @route ("/addReserJSON/new",name="addRes")
     */
    public function addRes(Request $request, NormalizerInterface $Normalizer, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation  = $em->getRepository(Reservation::class)->find($id);
        $reservation = new Reservation();
        $reservation->setNumVol($request->get('Num_vol'));
        $reservation->setDestination($request->get('Destination'));
        $reservation->setDateDepart($request->get('Date_depart'));
        $reservation->setDateArrivee($request->get('Date_arrivee'));
        $reservation->setAdulte($request->get('Adulte'));
        $reservation->setEnfant($request->get('Enfant'));



        $em->persist($reservation);
        $em->flush();
        $jsonContent = $Normalizer->normalize($reservation, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));;
    }
}
