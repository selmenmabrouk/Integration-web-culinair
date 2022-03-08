<?php

namespace App\Controller\front_end;

use App\Data\SearchData;
use App\Form\SearchForm;
use App\Repository\PlatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlatController extends AbstractController
{
    /**
     * @Route("/affichPlat", name="affichPlat")
     */
    public function afficherPlat(PlatRepository $repository, Request $request): Response
    {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
        $plat = $repository->findSearch($data);
        return $this->render('front_end/plat/AfficherPlat.html.twig', [
            'plat' => $plat,
            'form' => $form->createView()
        ]);

    }
}
