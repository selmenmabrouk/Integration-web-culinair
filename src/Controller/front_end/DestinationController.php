<?php

namespace App\Controller\front_end;


use App\Form\ContinentType;
use App\Form\DestinationType;
use App\Repository\ContinentRepository;
use App\Repository\PaysRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function App\Controller\dump;

/**
 * @Route("/destination")
 */
class DestinationController extends AbstractController
{

    /** 
     * @Route("/testJS", name="testJS")
     */
    public function avecJQuery(PaysRepository $rv, VilleRepository $rm, Request $request)
    {
        
        $pays = $rv->findAll();
        $ville = $rm->findAll();
        $form = $this->createForm(ContinentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump("Form submitted");
        }

        return $this->render('front_end/test/indexJS.html.twig', [
            'pays' => $pays,
            'ville' => $ville,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/", name="testEvent")
     */
    public function testListner(Request $request, ContinentRepository $repo): Response
    {
        $continent = $repo->findOneBy(["id" => 1]);
        $pays = $continent->getPays()[0];
        
        $destination = new Destination();
        $destination->setContinent($continent);
        $destination->setPays($pays);
        $destination->setVille(null);

        $form = $this->createForm(DestinationType::class, $destination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump("ici");
        }
        return $this->render('front_end/test/indexEvent.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
