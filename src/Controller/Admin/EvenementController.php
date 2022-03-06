<?php

namespace App\Controller\Admin;

use App\Entity\Promotion;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;
use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use DateTime;
use DateTimeZone;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EvenementController extends AbstractController
{
    /**
     * @Route("/evenement", name="evenement")
     */
    public function index(): Response
    {
        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }

    /**
     * @param EvenementRepository $repository
     * @return Response
     * @Route ("/",name ="afficheE")
     * @throws Exception
     */
    public function afficheEvent(EvenementRepository $repository)
    {


        $evenements = $repository->findAll();

        $evenementsForTwig = [];
        $i = 0;
        foreach ($evenements as $evenement) {
            $date = $evenement->getDate()->format("Y-m-d h:i:s");

            $date_now = date("Y-m-d h:i:s");
            $variable = new DateTime($date_now);
            //$to_compare = "2018-06-01 12:48:09";
            $variable1 = new DateTime($date);
            $difference = date_diff($variable, $variable1)->format("%y Année");
            $difference1 = date_diff($variable, $variable1)->format("%m Mois ");
            $difference2 = date_diff($variable, $variable1)->format(" %d Jours Restants");

            //  %m months,and %h hours
            $evenementsForTwig[$i]["id"] = $evenement->getId();
            $evenementsForTwig[$i]["enable"] = $evenement->getEnable();
            $evenementsForTwig[$i]["nom"] = $evenement->getNom();
            $evenementsForTwig[$i]["date"] = $evenement->getDate();
            $evenementsForTwig[$i]["difference"] = $difference;
            $evenementsForTwig[$i]["difference1"] = $difference1;
            $evenementsForTwig[$i]["difference2"] = $difference2;
            $evenementsForTwig[$i]["lieu"] = $evenement->getLieu();
            $evenementsForTwig[$i]["image"] = $evenement->getImage();
            $evenementsForTwig[$i]["promotion"]["tauxPromotion"] = $evenement->getPromotion()->getTauxPromotion();
            $evenementsForTwig[$i]["prix"] = $evenement->getPrix();
            $evenementsForTwig[$i]["Description"] = $evenement->getDescription();

            $i++;
        }

        return $this->render('base-front.html.twig', [
            'evenement' => $evenementsForTwig
        ]);
    }


    /**
     * @param EvenementRepository $repository
     * @return Response
     * @Route ("/back",name ="afficheE2")
     */

    public function afficheEvent2(EvenementRepository $repository, PromotionRepository $repo)
    {
        $event = $repository->findAll();
        $promotion = $repo->findAll();
        return $this->render('base-back.html.twig', [
            'event' => $event,
            'promotion' => $promotion
        ]);
    }

    /**
     * @Route("/details/{idevent}", name="detail")
     */
    public function details($idevent)
    {

        $evenement = $this->getDoctrine()->getRepository(Evenement::class)->find($idevent);

        return $this->render('evenement/detail.html.twig', [
            'evenement' => $evenement]);

    }

    /**
     * @Route ("/event/{id}",name="deleteEv")
     */
    public function deleteE($id, EvenementRepository $repository)
    {
        $evenement = $repository->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($evenement);
        $em->flush();
        return $this->redirectToRoute('afficheE2', [

        ]);

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/evenement/ajout",name="ajoutE")
     */

    public function ajouterP(\Symfony\Component\HttpFoundation\Request $request)
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
            return $this->redirectToRoute('afficheE2');
        }
        return $this->render('evenement/ajoutEvenement.html.twig', [
            'form' => $form->createView()]);
    }


    /**
     * @Route ("/evenement/update/{id}",name="updateE")
     */

    public function updateE(EvenementRepository $repository, \Symfony\Component\HttpFoundation\Request $request, $id)
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
            return $this->redirectToRoute('afficheE2');
        }
        return $this->render('evenement/updateEvent.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route ("/promotion/delete/{id}",name="deleteP")
     */
    public function deleteP(PromotionRepository $repo, $id)
    {
        $promotion = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($promotion);
        $em->flush();
        return $this->redirectToRoute('afficheE2');

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/promotion/ajout",name="ajoutP")
     */

    public function ajoutPromotio(\Symfony\Component\HttpFoundation\Request $request)
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->add('ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();
            return $this->redirectToRoute('afficheE2');
        }
        return $this->render('promotion/ajoutPromotion.html.twig', [
            'form' => $form->createView()]);
    }

    /**
     * @param PromotionRepository $repo
     * @param $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/promotion/update/{id}",name="updateP")
     */

    public function updatePromotion(PromotionRepository $repo, $id, \Symfony\Component\HttpFoundation\Request $request)
    {
        $promotion = $repo->find($id);
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->add('modifier', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('afficheE2');
        }
        return $this->render('promotion/updatePromotion.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/back/recherche", name="recherche_evenement")
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
     * @Route("back/{id}/masquer", name="masquer_evenement")
     */

    public function masquerEvent($id)
    {
        $evenement = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $evenement->setEnable(0);
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($evenement);
        $entityManager->flush();
        return $this->redirectToRoute('afficheE2');
    }

    /**
     * @Route("back/{id}/afficher", name="afficher_evenement")
     */
    public function afficherEvent($id)
    {
        $evenement = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $evenement->setEnable(1);
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($evenement);
        $entityManager->flush();
        return $this->redirectToRoute('afficheE2');
    }

}


