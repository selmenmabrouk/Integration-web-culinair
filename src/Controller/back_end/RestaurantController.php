<?php

namespace App\Controller\back_end;

use App\Entity\Restaurant;
use App\Form\RestaurantType;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Snipe\BanBuilder\CensorWords;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantController extends AbstractController
{


    /**
     * @Route("/affichRestaurantAdmin", name="affichRestaurantAdmin")
     */
    public function afficherRestaurantAdmin(Request $request): Response
    {
        $data = $this->getDoctrine()->getRepository(Restaurant::class)->findAll();
        //$dest = [];
        $dest = array();

        foreach ($data as $x) {
            //$dest[] = [$x->getDestination()] ;
            array_push($dest, $x->getType());
        }
        /*
        $destinations=array(); ;
        foreach ($data as $x)
        {
            $dest = $x->getDestination() ;
            array_push($destinations,$dest) ;
        }
        */

        if ($request->isMethod("POST")) {

            $type = $request->get('searchbar');
            if ($type != NULL) {
                $data = $this->getDoctrine()->getRepository(Restaurant::class)->findBy(array('type' => $type));
                if ($data == NULL) {
                    $data = $this->getDoctrine()->getRepository(Restaurant::class)->findAll();
                }
            } else {
                $data = $this->getDoctrine()->getRepository(Restaurant::class)->findAll();
            }

        }


        $array_dest_occ = array_count_values($dest);

        //['sadasd'=>2 , 'sadsad'=>5] ;

        /*foreach ($dest as $x)
        {
            if (array_search($x , $dest)!=-1  ) {
                $array_dest_occ[] = [$x , ]
            }

        }*/
        $final = [
            ['produit ', 'nom']

        ];
        //$array_dest_occ["Germany"];

        foreach ($array_dest_occ as $x => $x_value) {
            $final[] = [$x, (int)$x_value];
        }


        // charrtt
        $pieChart = new PieChart();
        /*$pieChart->getData()->setArrayToDataTable(
            [
                ['Country', 'Number of offres'],
                ['Work',     11],
                ['Eat',      2],
                ['Commute',  2],
                ['Watch TV', 2],
                ['Sleep',    7]
            ]
        );*/

        $pieChart->getData()->setArrayToDataTable($final);

        $pieChart->getOptions()->setTitle('Representation des restaurant ');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(700);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
        //
        /*
            return $this->render('back_end/offre/view.html.twig' ,  [
                'list' => $data
                //'list_dest' => $dest

                //'destinationx' =>$destinations
            ]   ) ;
        */
        //   return $this->render('back_end/produit/test.html.twig', array('piechart' => $pieChart));


        $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->findAll();

        return $this->render('back_end/restaurant/AfficherRestaurantAdmin.html.twig', [
            "Restaurant" => $restaurant,
            'piechart' => $pieChart,
            'list' => $data,
            'test' => $final,

        ]);
    }

    /**
     * @Route("/ajoutRestaurant", name="ajoutRestaurant")
     */
    public function ajoutRestaurant(Request $request): Response
    {
        $basic = new \Vonage\Client\Credentials\Basic("30edd373", "GE9fUCqc4xS6MKLn");
        $client = new \Vonage\Client($basic);
        $restaurant = new Restaurant();
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */

            $file = $form->get('Photo')->getData();
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

            // moves the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('restaurant_directory'),
                $fileName
            );
            $contenuComment = $form->getData()->getDescription();

            $censor = new CensorWords;
            $badwords = $censor->setDictionary('fr');
            $cleanedComment = $censor->censorString($contenuComment);
            $restaurant->setDescription($cleanedComment['clean']);

            $response = $client->sms()->send(
                new \Vonage\SMS\Message\SMS("21644196276", "culinair", 'un nouveau restaurant a ete ajouté')
            );

            $message = $response->current();

            if ($message->getStatus() == 0) {
                echo "The message was sent successfully\n";
            } else {
                echo "The message failed with status: " . $message->getStatus() . "\n";
            }


            // updates the 'brochure' property to store the PDF file name
            // instead of its contents
            $restaurant->setPhoto($fileName);
            $entityManager->persist($restaurant);
            $entityManager->flush();
            return $this->redirectToRoute('affichRestaurantAdmin');
        }


        return $this->render("back_end/Restaurant/AjoutRestaurant.html.twig", [
            "form_title" => "Ajouter un restaurant",
            "form_restaurant" => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifierRestaurant/{id}", name="modifierRestaurant")
     */
    public function modifyRestaurant(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $restaurant = $entityManager->getRepository(Restaurant::class)->find($id);
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */

            $file = $form->get('Photo')->getData();
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

            // moves the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('restaurant_directory'),
                $fileName
            );

            // updates the 'brochure' property to store the PDF file name
            // instead of its contents
            $restaurant->setPhoto($fileName);

            $entityManager->flush();
            $this->addFlash('success', 'La modification a été effectué');
            return $this->redirectToRoute("affichRestaurantAdmin");
        }

        return $this->render("back_end/restaurant/ModifierRestaurant.html.twig", [
            "form_title" => "Modifier un restaurant",
            "form_restaurant" => $form->createView(),
        ]);
    }

    /**
     * @Route("/deleteRestaurant/{id}", name="deleteRestaurant")
     */
    public function deletetRestaurant(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $restaurant = $entityManager->getRepository(Restaurant::class)->find($id);
        $entityManager->remove($restaurant);
        $entityManager->flush();
        $this->addFlash('success', 'L"action a été effectué');


        return $this->redirectToRoute("affichRestaurantAdmin");
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
