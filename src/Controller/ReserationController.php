<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ReservationRepository;


class ReserationController extends AbstractController
{
    /**
     * @Route("/reseration", name="reseration")
     */
    public function index(): Response
    {

        return $this->render('reseration/index.html.twig', [
            'controller_name' => 'ReserationController',
        ]);
    }




    /**
     * @Route("/Reservation_Detail/{id}" , name = "reservation_detail")
     */
    //ReservationDetail
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
        return $this->render('reservation/reservation_detail.html.twig', [
            'data' => $event,
            'formA' => $form->createView()
        ]);
    }

    /**
     * @Route("/affichreservation", name="affichreservation")
     */
    public function AfficherReservation(Request $request, PaginatorInterface $paginator): Response
    {
        $reservation = $this->getDoctrine()->getRepository(reservation::class)->findAll();
        $reservation = $paginator->paginate(
            $reservation, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        return $this->render('reservation/AfficherReservationAdmin.html.twig', [
            'controller_name' => 'ReserationController',
            "Reservation" => $reservation,

        ]);
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


        return $this->redirectToRoute("affichreservation");
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

        return $this->render("reservation/ModifierReservation.html.twig", [
            "form_title" => "Modifier une Reservation",
            "form_Reservation" => $form->createView(),
        ]);
    }
}
