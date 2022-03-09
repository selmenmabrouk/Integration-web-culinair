<?php

namespace App\Controller\back_end;

use App\Entity\Voyage;
use App\Form\VoyageType;
use App\Repository\VoyageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/voyage")
 */
class VoyageController extends AbstractController
{
    /**
     * @Route("/admin", name="voyage_indexback", methods={"GET"})
     */
    public function indexback(VoyageRepository $voyageRepository): Response
    {
        return $this->render('back_end/voyage/indexback.html.twig', [
            'voyages' => $voyageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/admin", name="voyage_newback", methods={"GET", "POST"})
     */
    public function newback(Request $request, EntityManagerInterface $entityManager): Response
    {
        $voyage = new Voyage();
        $form = $this->createForm(VoyageType::class, $voyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($voyage);
            $entityManager->flush();

            return $this->redirectToRoute('voyage_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_end/voyage/newback.html.twig', [
            'voyage' => $voyage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/admin", name="voyage_showback", methods={"GET"})
     */
    public function showback(Voyage $voyage): Response
    {
        return $this->render('back_end/voyage/showback.html.twig', [
            'voyage' => $voyage,
        ]);
    }


    /**
     * @Route("/{id}/edit/admin", name="voyage_editback", methods={"GET", "POST"})
     */
    public function editback(Request $request, Voyage $voyage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VoyageType::class, $voyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('voyage_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_end/voyage/editback.html.twig', [
            'voyage' => $voyage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="voyage_delete", methods={"POST"})
     */
    public function deleteBack(Request $request, Voyage $voyage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $voyage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($voyage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('voyage_indexback', [], Response::HTTP_SEE_OTHER);
    }
}
