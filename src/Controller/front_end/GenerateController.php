<?php

namespace App\Controller\front_end;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenerateController extends AbstractController
{
    /**
     * @Route("/generate/{id}", name="generate")
     */
    public function index(string $id): Response
    {
        //  $response = new QrCodeResponse($result);

        $qrCode = "http://chart.apis.google.com/chart?cht=qr&chs=150x150&chl= Votre reservation à été effectuer à cette date : " . $id;


        return $this->render('front_end/reservation/index.html.twig', [
            'qrCode' => $qrCode,

        ]);
    }
}
