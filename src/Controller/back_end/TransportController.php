<?php

namespace App\Controller\back_end;

use App\Entity\Transport;
use App\Form\TransportType;
use App\Repository\TransportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/transport")
 */
class TransportController extends AbstractController
{
    /**
     * @Route("/", name="transport_index", methods={"GET"})
     */
    public function index(TransportRepository $transportRepository, EntityManagerInterface $em, Request $request, PaginatorInterface $paginator): Response
    {
        $dql = "SELECT a FROM \App\Entity\Transport a";
        $query = $em->createQuery($dql);

        $transport = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );
        return $this->render('back_end/transport/index.html.twig', [
            'transports' => $transport,
        ]);
    }

    /**
     * @Route("/new", name="transport_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $transport = new Transport();
        $form = $this->createForm(TransportType::class, $transport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($transport);
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Added successfully!');
            return $this->redirectToRoute('transport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_end/transport/new.html.twig', [
            'transport' => $transport,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="transport_show", methods={"GET"})
     */
    public function show(Transport $transport): Response
    {
        return $this->render('back_end/transport/show.html.twig', [
            'transport' => $transport,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="transport_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Transport $transport, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TransportType::class, $transport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('transport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_end/transport/edit.html.twig', [
            'transport' => $transport,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="transport_delete", methods={"POST"})
     */
    public function delete(Request $request, Transport $transport, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $transport->getId(), $request->request->get('_token'))) {
            $entityManager->remove($transport);
            $entityManager->flush();
        }

        return $this->redirectToRoute('transport_index', [], Response::HTTP_SEE_OTHER);
    }
}
