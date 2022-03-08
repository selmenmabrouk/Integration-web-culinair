<?php

namespace App\Controller\back_end;

use App\Entity\Reservation;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/reservation")
 */
class ReservationController extends AbstractController
{
    /**
     * @Route("", name="reservation_index_admin")
     */
    public function AfficherReservation(Request $request, PaginatorInterface $paginator): Response
    {
        $reservation = $this->getDoctrine()->getRepository(reservation::class)->findAll();
        $reservation = $paginator->paginate(
            $reservation, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        return $this->render('back_end/reservation/index.html.twig', [
            'controller_name' => 'ReserationController',
            "Reservation" => $reservation,
        ]);
    }

    /**
     * @Route("/{id}",name="Resid")
     * @throws ExceptionInterface
     */
    public function Resid(Request $request, $id, NormalizerInterface $Normalizer): Response
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository(Reservation::class)->find($id);
        $jsonContent = $Normalizer->normalize($reservation, 'json', ['groups' => 'post:read']);

        return $this->render('back_end/reservation_json/index.html.twig', ['data' => $jsonContent,]);
    }

    /**
     * @Route("/deletereservation/{id}", name="deletereservation")
     */
    public function deleteReservation(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Reservation = $entityManager->getRepository(Reservation::class)->find($id);
        $entityManager->remove($Reservation);
        $entityManager->flush();
        $this->addFlash('success', 'L"action a été effectué');


        return $this->redirectToRoute("reservation_index_admin");
    }

    /**
     * @Route("/allReservationJSON", name="all_Reservation")
     * @throws ExceptionInterface
     */
    public function liste(NormalizerInterface $Normalizer): Response
    {
        $repository = $this->getDoctrine()->getRepository(Reservation::class);
        $reservation = $repository->findAll();
        $jsonContent = $Normalizer->normalize($reservation, 'json', ['groups' => 'post:read']);

        return $this->render('back_end/reservation\allReservationJSON.html.twig', ['data' => $jsonContent,]);
    }

    /**
     *
     * @route ("/addReserJSON/new",name="addRes")
     * @throws ExceptionInterface
     */
    public function addRes(Request $request, NormalizerInterface $Normalizer, $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository(Reservation::class)->find($id);
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
        return new Response(json_encode($jsonContent));
    }
}
