<?php

namespace App\Controller\back_end;

use App\Entity\Pays;
use App\Form\PaysType;
use App\Repository\PaysRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/pays")
 */
class PaysController extends AbstractController
{

    /**
     * @Route("/admin", name="pays_indexback", methods={"GET"})
     */
    public function indexback(PaysRepository $paysRepository): Response
    {
        return $this->render('back_end/pays/indexback.html.twig', [
            'pays' => $paysRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/admin", name="pays_newback", methods={"GET", "POST"})
     */
    public function newback(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pay = new Pays();
        $form = $this->createForm(PaysType::class, $pay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pay);
            $entityManager->flush();

            return $this->redirectToRoute('pays_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_end/pays/newback.html.twig', [
            'pay' => $pay,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/admin", name="pays_showback", methods={"GET"})
     */
    public function showback(Pays $pay): Response
    {
        return $this->render('back_end/pays/showback.html.twig', [
            'pay' => $pay,
        ]);
    }

    /**
     * @Route("/{id}/edit/admin", name="pays_editback", methods={"GET", "POST"})
     */
    public function editback(Request $request, Pays $pay, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaysType::class, $pay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('pays_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_end/pays/editback.html.twig', [
            'pay' => $pay,
            'form' => $form->createView(),
        ]);
    }

}
