<?php

namespace App\Controller\front_end;

use App\Data\SearchData;
use App\Form\SearchFormType;
use App\Repository\PlatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlatController extends AbstractController
{
    /**
     * @Route("/afficherPlatAll", name="afficherPlatAll")
     */
    public function afficherPlatAll(PlatRepository $repository, Request $request): Response
    {
        return $this->render('front_end/plat/AfficherPlatAll.html.twig', [
            'plat' => $repository->findAll(),
        ]);

    }

    /**
     * @Route("/afficherPlat", name="afficherPlat")
     */
    public function afficherPlat(PlatRepository $repository, Request $request): Response
    {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchFormType::class, $data);
        $form->handleRequest($request);
        $plat = $repository->findSearch($data);

        return $this->render('front_end/plat/AfficherPlat.html.twig', [
            'plat' => $plat,
            'form' => $form->createView()
        ]);

    }
}
