<?php

namespace App\Controller\front_end;

use App\Entity\Activite;
use App\Entity\ActiviteLike;
use App\Repository\ActiviteLikeRepository;
use App\Repository\ActiviteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/activite")
 */
class ActiviteController extends AbstractController
{
    /**
     * @Route("/now", name="activite_front", methods={"GET"})
     */
    public function activite_front(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('front_end/activite/index.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);
    }

    /**
     * @route ("/{id}/like",name="Activite_like")
     * @param Activite $Activite
     * @param ActiviteLikeRepository $likeRepo
     * @return Response
     */
    public function like(Activite $Activite, ActiviteLikeRepository $likeRepo): Response
    {
        $user = $this->getUser();
        if (!$user)
            return $this->redirectToRoute("activite_front");
        if ($Activite->isLikedByUser($user)) {
            $like = $likeRepo->findOneBy([
                'Activite' => $Activite,
                'user' => $user]);
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($like);
            $entityManager->flush();

            return $this->redirectToRoute("activite_front");

        }
        $like = new ActiviteLike();
        $like->setActivite($Activite)
            ->setUser($user);
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($like);
        $entityManager->flush();

        return $this->redirectToRoute("activite_front");
    }

}
