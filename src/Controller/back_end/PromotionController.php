<?php

namespace App\Controller\back_end;

use App\Entity\Promotion;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/admin/promotion")
 */
class PromotionController extends AbstractController
{

    /**
     * @Route ("/promotion/delete/{id}",name="promotion_delete")
     */
    public function delete(PromotionRepository $repo, $id): RedirectResponse
    {
        $promotion = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($promotion);
        $em->flush();
        return $this->redirectToRoute('activite_index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route ("/promotion/ajout",name="promotion_new")
     */
    public function new(Request $request)
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->add('ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();
            return $this->redirectToRoute('activite_index');
        }
        return $this->render('back_end/promotion/ajoutPromotion.html.twig', [
            'form' => $form->createView()]);
    }

    /**
     * @param PromotionRepository $repo
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route ("/promotion/update/{id}",name="promotion_edit")
     */

    public function edit(PromotionRepository $repo, $id, Request $request)
    {
        $promotion = $repo->find($id);
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->add('modifier', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('activite_index');
        }
        return $this->render('back_end/promotion/updatePromotion.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
