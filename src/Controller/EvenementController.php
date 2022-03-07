<?php

namespace App\Controller;
use App\Controller\FileException;
use App\Entity\Promotion;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;
use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     */

    public function afficheEvent(EvenementRepository $repository)
    {
        $evenement = $repository->findAll();
        return $this->render('index.html.twig', [
            'data' => $evenement
        ]);
    }


    /**
     * @param EvenementRepository $repository
     * @return Response
     * @Route ("/admin",name ="afficheE2")
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
     * @Route ("/event/{id}",name="deleteEv")
     */
    public function deleteE($id, EvenementRepository $repository)
    {
        $evenement = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($evenement);
        $em->flush();
        return $this->redirectToRoute('afficheE2');
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
            'form' => $form->createView()
        ]);
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
            'form' => $form->createView()
        ]);
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
}
