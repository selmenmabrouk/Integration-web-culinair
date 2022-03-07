<?php

namespace App\Controller\front_end;

use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvenementController extends AbstractController
{
    /**
     * @Route("evenement/details/{idevent}", name="evenement_detail")
     */
    public function details($idevent): Response
    {
        $evenement = $this->getDoctrine()->getRepository(Evenement::class)->find($idevent);

        return $this->render('front_end/evenement/detail.html.twig', [
                'evenement' => $evenement]
        );
    }

    /**
     * @Route ("/", name="accueil")
     * @param EvenementRepository $repository
     * @return Response
     * @throws Exception
     */
    public function accueil(EvenementRepository $repository): Response
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

        return $this->render('front_end/evenement/index.html.twig', [
            'evenement' => $evenementsForTwig
        ]);
    }
}


