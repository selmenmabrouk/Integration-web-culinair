<?php

namespace App\Controller\Admin;

use App\Entity\Activite;
use App\Entity\ActiviteSearch;
use App\Form\ActiviteType;
use App\Form\ActiviteSearchType;
use App\Repository\ActiviteRepository;
use ContainerOXwWGiq\PaginatorInterface_82dac15;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/activite")
 */
class ActiviteController extends AbstractController
{
    private $repository;
    /**
     * @Route("/", name="activite_index" )
     */
    public function index(EntityManagerInterface $em,ActiviteRepository $activiteRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $ActiviteSearch = new ActiviteSearch();
        $form = $this->createForm(ActiviteSearchType::class,$ActiviteSearch);
        $form->handleRequest($request);
        //initialement le tableau des activite est vide,
        //c.a.d on affiche les activite que lorsque l'utilisateur clique sur le bouton rechercher
        $activite = $activiteRepository->findAll();


        if($form->isSubmitted() && $form->isValid()) {
            $activite= [];
            //on récupère le nom d'Activite tapé dans le formulaire
            $nom = $ActiviteSearch->getNom();
            if ($nom!="")
                //si on a fourni un nom d'Activite on affiche tous les activite ayant ce nom
                $activite= $this->getDoctrine()->getRepository(Activite::class)->findBy(['nom' => $nom] );
            else
                //si si aucun nom n'est fourni on affiche tous les activite
                $activite= $this->getDoctrine()->getRepository(Activite::class)->findAll();
        }


        return  $this->render('Activite/index.html.twig',['form' =>$form->createView(), 'activites' => $activite ]);
    }


    /**
     * @Route("/now", name="activite_front", methods={"GET"})
     */
    public function activite_front(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('activite/ActiviteFront.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="activite_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($activite);
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Added successfully!'

            );


            return $this->redirectToRoute('activite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('activite/ajouterActiviteAdmin.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="activite_show", methods={"GET"})
     */
    public function show(Activite $activite): Response

    {
        return $this->render('activite/show.html.twig', [
            'activite' => $activite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="activite_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($activite);
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Added successfully!' );
            return $this->redirectToRoute('activite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('activite/edit.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="activite_delete", methods={"POST"})
     */
    public function delete(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getId(), $request->request->get('_token'))) {
            $entityManager->remove($activite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('activite_index', [], Response::HTTP_SEE_OTHER);
    }
}
