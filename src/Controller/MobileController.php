<?php

namespace App\Controller;

use App\Entity\Plat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;


class MobileController extends AbstractController
{

    /**
     * @Route("/displayPlatMobile", name="displayPlatMobile")
     */
    public function displayPlatMobile(Request $request, SerializerInterface $serializer): Response
    {
        $plat = $this->getDoctrine()->getRepository(Plat::class)->findAll();
        $formatted = $serializer->normalize($plat, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($formatted));
    }

    /**
     * @Route("/addPlatMobile", name="addPlatMobile")
     */
    public function addPlatMobile(Request $request, SerializerInterface $serializer): Response
    {

        $plat = new Plat();
        $id = $request->query->get("Id");
        $Nom = $request->query->get("Nom");
        $Type_cuisine = $request->query->get("TypeCuisine");
        $Description = $request->query->get("Description");
        $Prix = $request->query->get("Prix");

        $plat->setNom($Nom);
        $plat->setTypeCuisine($Type_cuisine);
        $plat->setDescription($Description);
        $plat->setPrix($Prix);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($plat);
        $entityManager->flush();

        $formatted = $serializer->normalize($plat, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($formatted));
    }

    /**
     * @Route("/deletePlatMobile", name="deletePlatMobile")
     */
    public function deletePlatMobile(Request $request, SerializerInterface $serializer): Response
    {
        $id = $request->query->get("id");
        $entityManager = $this->getDoctrine()->getManager();
        $plat = $entityManager->getRepository(Plat::class)->find($id);
        if ($plat != null) {
            $entityManager->remove($plat);
            $entityManager->flush();
            $formatted = $serializer->normalize($plat, 'json', ['groups' => 'post:read']);
            return new Response(json_encode($formatted));

        }

        return new Response("le plat invalide");
    }

    /**
     * @Route("/updatePlatMobile", name="updatePlatMobile")
     */
    public function updatePlatMobile(Request $request, SerializerInterface $serializer): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $plat = $entityManager->getRepository(Plat::class)->find($request->get("id"));
        $id = $request->query->get("Id");
        $Nom = $request->query->get("Nom");
        $Type_cuisine = $request->query->get("TypeCuisine");
        $Description = $request->query->get("Description");
        $Prix = $request->query->get("Prix");
        $plat->setNom($Nom);
        $plat->setTypeCuisine($Type_cuisine);
        $plat->setDescription($Description);
        $plat->setPrix($Prix);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($plat);
        $entityManager->flush();

        $formatted = $serializer->normalize($plat, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($formatted));
    }
}
