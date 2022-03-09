<?php

namespace App\Controller\front_end;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\EvenementRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ReservationController extends AbstractController
{
    /**
     * @Route("/Reservation_index" , name = "reservation_index")
     */
    public function index(): Response
    {
        return $this->render('front_end/reservation/index.html.twig', [
            'evenements' => $event = $this->getDoctrine()->getRepository(Evenement::class)->findAll(),
            'reservations' => $this->getDoctrine()->getRepository(Reservation::class)->findAll()
        ]);
    }

    /**
     * @Route("/Reservation_Detail/{id}" , name = "reservation_detail")
     */
    public function addReservation(Request $request, $id, EvenementRepository $rep): Response
    {

        $event = $rep->find($id);
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setEvent($event);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('generate', ['id' => date_format($reservation->getDateArrivee(), "Y:m:d H:i:s")]);

            ////return $this->render( hot path mtaa page mteaak bech thezek il paiement !!);
        }
        return $this->render('front_end/reservation/reservation_detail.html.twig', [
            'data' => $event,
            'formA' => $form->createView()
        ]);
    }

    /**
     * @Route("/generate/{id}", name="generate")
     */
    public function generate(string $id): Response
    {
        //  $response = new QrCodeResponse($result);

        $qrCode = "http://chart.apis.google.com/chart?cht=qr&chs=150x150&chl= Votre reservation à été effectuer à cette date : " . $id;

        return $this->render('front_end/reservation/generate.html.twig', [
            'qrCode' => $qrCode,
        ]);
    }
}
