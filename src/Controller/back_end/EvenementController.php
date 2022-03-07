<?php

namespace App\Controller\back_end;

use App\Entity\Evenement;
use App\Entity\Promotion;
use App\Form\EvenementType;
use App\Form\PromotionType;
use App\Repository\EvenementRepository;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/admin/evenement")
 */
class EvenementController extends AbstractController
{
    /**
     * @param EvenementRepository $repository
     * @param PromotionRepository $repo
     * @return Response
     * @Route ("/",name ="evenement_index")
     */
    public function index(EvenementRepository $repository, PromotionRepository $repo): Response
    {
        $evenements = $repository->findAll();
        $promotions = $repo->findAll();
        return $this->render('back_end/evenement/index.html.twig', [
            'evenements' => $evenements,
            'promotions' => $promotions
        ]);
    }

    /**
     * @Route ("/{id}/delete",name="evenement_delete")
     */
    public function delete($id, EvenementRepository $repository): RedirectResponse
    {
        $evenement = $repository->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($evenement);
        $em->flush();
        return $this->redirectToRoute('evenement_index', [

        ]);

    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/new", name="evenement_new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->add('ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $ev = $form->getData();
            $img = $form->get('image')->getData();
            $upload = md5(uniqid()) . '.' . $img->guessExtension();
            try {
                $img->move(
                    $this->getParameter('images_directory'),
                    $upload
                );

            } catch (FileException $e) {

            }
            $ev->setImage($upload);
            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();
            return $this->redirectToRoute('evenement_index');
        }
        return $this->render('back_end/evenement/ajoutEvenement.html.twig', [
            'form' => $form->createView()]);
    }

    /**
     * @Route("/{id}/edit", name="evenement_edit", methods={"GET", "POST"})
     */
    public function edit(EvenementRepository $repository, Request $request, $id)
    {
        $evenement = $repository->find($id);
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->add('update', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ev = $form->getData();
            $img = $form->get('image')->getData();
            $upload = md5(uniqid()) . '.' . $img->guessExtension();
            try {
                $img->move(
                    $this->getParameter('images_directory'),
                    $upload
                );

            } catch (FileException $e) {

            }
            $ev->setImage($upload);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('evenement_index');
        }
        return $this->render('back_end/evenement/updateEvent.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/recherche", name="recherche_evenement")
     */
    public function rechercheEvent(Request $request, NormalizerInterface $normalizer): Response
    {
        $event = $request->get("valeur-recherche");
        $recs = $this->getDoctrine()->getRepository(Evenement::class)->findStartingWith($event);

        $recsJson = [];
        $i = 0;
        foreach ($recs as $rec) {
            $recsJson[$i]["id"] = $rec->getId();
            $recsJson[$i]["nom"] = $rec->getNom();
            $recsJson[$i]["date"] = $rec->getDate()->format('d-m-Y');
            $recsJson[$i]["lieu"] = $rec->getLieu();
            $recsJson[$i]["prix"] = $rec->getPrix();
            $recsJson[$i]["promotion"] = $rec->getPromotion()->getTauxPromotion();


            $i++;
        }
        return new Response(json_encode($recsJson));
    }

    /**
     * @Route("admin/{id}/masquer", name="masquer_evenement")
     */
    public function masquerEvent($id): RedirectResponse
    {
        $evenement = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $evenement->setEnable(0);
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($evenement);
        $entityManager->flush();
        return $this->redirectToRoute('evenement_index');
    }

    /**
     * @Route("admin/{id}/afficher", name="afficher_evenement")
     */
    public function afficherEvent($id): RedirectResponse
    {
        $evenement = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $evenement->setEnable(1);
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($evenement);
        $entityManager->flush();
        return $this->redirectToRoute('evenement_index');
    }
}


