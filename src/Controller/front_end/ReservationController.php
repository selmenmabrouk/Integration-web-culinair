<?php

namespace App\Controller\front_end;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\EvenementRepository;
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
     * @Route("/modifyResevation/{id}", name="modifyResevation")
     */
    public function modifyResevation(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $Reservation = $entityManager->getRepository(Reservation::class)->find($id);
        $form = $this->createForm(ReservationType::class, $Reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            //  $file = $guide->getPhoto();


            // updates the 'brochure' property to store the PDF file name
            // instead of its contents
            //$Reservation->setPhoto($fileName);

            $entityManager->flush();
            $this->addFlash('success', 'L"action a été effectué');
            return $this->redirectToRoute("affichreserationAdmin");
        }

        return $this->render('front_end/reservation/ModifierReservation.html.twig', [
            "form_title" => "Modifier une Reservation",
            "form_Reservation" => $form->createView(),
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
