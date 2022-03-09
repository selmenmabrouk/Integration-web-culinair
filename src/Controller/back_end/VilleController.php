<?php

namespace App\Controller\back_end;

use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ville")
 */
class VilleController extends AbstractController
{
    /**
     * @Route("/admin", name="ville_indexback", methods={"GET"})
     */
    public function indexback(VilleRepository $villeRepository): Response
    {
        return $this->render('back_end/ville/indexback.html.twig', [
            'villes' => $villeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/admin", name="ville_newback", methods={"GET", "POST"})
     */
    public function newback(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ville = new Ville();
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();

            return $this->redirectToRoute('ville_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_end/ville/newback.html.twig', [
            'ville' => $ville,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/admin", name="ville_showback", methods={"GET"})
     */
    public function showback(Ville $ville): Response
    {
        return $this->render('back_end/ville/showback.html.twig', [
            'ville' => $ville,
        ]);
    }

    /**
     * @Route("/{id}/edit/admin", name="ville_editback", methods={"GET", "POST"})
     */
    public function editback(Request $request, Ville $ville, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('ville_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_end/ville/editback.html.twig', [
            'ville' => $ville,
            'form' => $form->createView(),
        ]);
    }
}
