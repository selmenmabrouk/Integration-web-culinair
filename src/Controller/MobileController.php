<?php

namespace App\Controller;
use App\Entity\Activite;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class MobileController extends AbstractController
{
    /**
     * @Route("/mobile", name="mobile")
     */
    public function index(): Response
    {
        return $this->render('mobile/index.html.twig', [
            'controller_name' => 'MobileController',
        ]);
    }
    /**
     * @Route("/displayActiviteMobile", name="displayActiviteMobile")
     */
    public function displayActiviteMobile(Request $request, SerializerInterface $serializer): Response
    {
        $Activite = $this->getDoctrine()->getRepository(Activite::class)->findAll();
        $formatted = $serializer->normalize($Activite,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
    /**
     * @Route("/addActiviteMobile", name="addActiviteMobile")
     */
    public function addActiviteMobile(Request $request, SerializerInterface $serializer): Response
    {

        $Activite = new Activite();
        $nom=$request->query->get("nom") ;
        $type_activite=$request->query->get("type_activite") ;
        $description=$request->query->get("description") ;
        $prix_activite=$request->query->get("prix_activite") ;
        $temps=$request->query->get("temps") ;
        $nombre_participant=$request->query->get("nombre_participant") ;

        $Activite->setNom($nom) ;
        $Activite->setTypeActivite($type_activite) ;
        $Activite->setDescription($description) ;
        $Activite->setPrix($prix) ;
        $Activite->setTemps($temps) ;
        $Activite->setnombre_participant($nombre_participant) ;


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($Activite);
        $entityManager->flush();

        $formatted = $serializer->normalize($Activite,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;



    }
    /**
     * @Route("/deleteActiviteMobile", name="deleteActiviteMobile")
     */
    public function deleteActiviteMobile(Request $request, SerializerInterface $serializer): Response
    {
        $id=$request->query->get("id") ;
        $entityManager = $this->getDoctrine()->getManager();
        $Activite = $entityManager->getRepository(Activite::class)->find($id);
        if($Activite!=null){
            $entityManager->remove($Activite);
            $entityManager->flush();
            $formatted = $serializer->normalize($Activite,'json',['groups' => 'post:read']);
            return new Response(json_encode($formatted)) ;

        }


        return new Response("la Activite invalide") ;
    }
    /**
     * @Route("/updateActiviteMobile", name="updateActiviteMobile")
     */
    public function updateActiviteMobile(Request $request, SerializerInterface $serializer): Response
    {

        $entityManager = $this->getDoctrine()->getManager() ;

        $Activite = $entityManager->getRepository(Activite::class)->find($request->get("id")) ;
        $nom=$request->query->get("nom") ;
        $description=$request->query->get("description") ;
        $Activite->setNom($nom) ;

        $Activite->setdescription($description) ;

        $entityManager = $this->getDoctrine()->getManager() ;
        $entityManager->persist($Activite) ;
        $entityManager->flush() ;

        $formatted = $serializer->normalize($Activite,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;



    }
}
