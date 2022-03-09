<?php

namespace App\Controller\back_end;

use App\Entity\Continent;
use App\Form\ContinentType;
use App\Repository\ContinentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/continent")
 */
class ContinentController extends AbstractController
{
    /**
     * @Route("/admin", name="continent_indexback", methods={"GET"})
     */
    public function indexback(ContinentRepository $continentRepository): Response
    {
        return $this->render('back_end/continent/indexback.html.twig', [
            'continents' => $continentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/back", name="continent_new_back", methods={"GET", "POST"})
     */
    public function newback(Request $request, EntityManagerInterface $entityManager): Response
    {
        $continent = new Continent();
        $form = $this->createForm(ContinentType::class, $continent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($continent);
            $entityManager->flush();

            return $this->redirectToRoute('continent_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_end/continent/newback.html.twig', [
            'continent' => $continent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/admin", name="continent_showback", methods={"GET"})
     */
    public function showback(Continent $continent): Response
    {
        return $this->render('back_end/continent/showback.html.twig', [
            'continent' => $continent,
        ]);
    }

    /**
     * @Route("/{id}/edit/admin", name="continent_editback", methods={"GET", "POST"})
     */
    public function editback(Request $request, Continent $continent, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContinentType::class, $continent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('continent_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_end/continent/editback.html.twig', [
            'continent' => $continent,
            'form' => $form->createView(),
        ]);
    }
}
