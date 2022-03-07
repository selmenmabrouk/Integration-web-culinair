<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCodeBundle\Response\QrCodeResponse;


class GenerateController extends AbstractController
{


    /**
     * @Route("/generate/{id}", name="generate")
     */
    public function index(string $id): Response
    {
        //  $response = new QrCodeResponse($result);

        $qrCode = "http://chart.apis.google.com/chart?cht=qr&chs=150x150&chl= Votre reservation à été effectuer à cette date : " . $id;


        return $this->render('generate/index.html.twig', [
            'qrCode' => $qrCode,

        ]);
    }
}
