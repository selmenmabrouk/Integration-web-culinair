<?php

namespace App\Controller\front_end;

use App\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ReservationJsonController extends AbstractController
{
    /**
     * @Route("/reservation/json", name="reservation_json")
     */
    public function index(): Response
    {
        return $this->render('front_end/reservation/index_json.html.twig', [
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
        return $this->render('front_end/reservation/index_json.html.twig', ['data' => $jsonContent,]);
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
        return $this->render('front_end/reservation/allReservationJSON.html.twig', ['data' => $jsonContent,]);
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
