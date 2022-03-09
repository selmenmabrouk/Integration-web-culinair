<?php

namespace App\Controller\front_end;

use App\Entity\Voyage;
use App\Form\VoyageType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\VoyageRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/voyage")
 */
class VoyageController extends AbstractController
{
    /**
     * @Route("/list", name="voyage_index", methods={"GET"})
     */
    public function index(VoyageRepository $voyageRepository): Response
    {
        return $this->render('front_end/voyage/index.html.twig', [
            'voyages' => $voyageRepository->findAll(),
        ]);
    }
 /**
 * @Route("/listV", name="list_voyage", methods={"GET"})
 */
    public function listV(VoyageRepository $voyageRepository): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);


        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('front_end/voyage/listV.html.twig', [
            'voyages' => $voyageRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);

        return new Response();
    }

    /**
     * @Route("/{id}/map", name="event_show_user", methods={"POST","GET"})
     */
    public function showmap(Voyage $event): Response
    {

        if (isset($_POST["submit_address"]))
        {
            $address = $_POST["address"];
            $address = str_replace(" ", "+", $address);
            ?>

            <iframe width="100%" height="500" src="https://maps.google.com/maps?q=<?php echo $address; ?>&output=embed"></iframe>

            <?php
        }
        //return $this->render('front_end/event/map.html.twig');

        return $this->render('front_end/voyage/show.html.twig', [
            'voyage' => $event,
        ]);
    }

    /**
     * @Route("/", name="voyage_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $voyage = new Voyage();
        $form = $this->createForm(VoyageType::class, $voyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($voyage);
            $entityManager->flush();

            return $this->redirectToRoute('voyage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front_end/voyage/new.html.twig', [
            'voyage' => $voyage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recherche/voyage", name="recherche_voyage")
     */
    public function searchAction(Request $request)
    {

        $data = $request->request->get('search');


        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT v FROM App\Entity\Voyage v WHERE v.prix  LIKE :data')
            ->setParameter('data', '%' . $data . '%');


        //SELECT * FROM `voyage` v INNER JOIN ville vi ON v.ville_id=vi.id WHERE vi.nom_ville = 'paris'

        $voyages = $query->getResult();

        return $this->render('front_end/voyage/index.html.twig', [
            'voyages' => $voyages,
        ]);

    }

    /**
     * @Route("/{id}/show", name="voyage_show", methods={"GET"})
     */
    public function show(Voyage $voyage): Response
    {
        return $this->render('front_end/voyage/show.html.twig', [
            'voyage' => $voyage,
        ]);
    }

    /**
     * @Route("/tri/prix", name="triprix")
     */
    public function TriActionPrix(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT v FROM App\Entity\Voyage v
    ORDER BY v.prix ASC');


        $voyages = $query->getResult();

        return $this->render('front_end/voyage/index.html.twig', [
            'voyages' => $voyages,
        ]);

    }
    /**
     * @Route("/pdf", name="pdf")
     */
    public function GenererPdf()
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('front_end/voyage/index.html.twig', [
            'title' => "Welcome to our PDF Voyage"
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }

    /**
     * @Route("/tri/date", name="tridatedepart")
     */
    public function TriActionDateDepart(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT v FROM App\Entity\Voyage v
    ORDER BY v.dateDepart ASC');


        $voyages = $query->getResult();

        return $this->render('front_end/voyage/index.html.twig', [
            'voyages' => $voyages,
        ]);

    }




}
