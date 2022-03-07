<?php

namespace App\Controller\back_end;

use App\Entity\User;
use App\Form\UserUpdateFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class UserController extends AbstractController
{
    /**
     * @Route("")
     */
    public function accueil(): Response
    {
        return $this->redirect("/admin/user/showAll");
    }

    /**
     * @Route("/user/showAll", name="list_users")
     */
    public function list(ManagerRegistry $doctrine): Response
    {
        $users = $doctrine->getRepository(User::class)->findAll();
        return $this->render('back_end/user/showAll.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/user/show={id}", name="user")
     */
    public function index(ManagerRegistry $doctrine, int $id): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id);

        return $this->render('back_end/user/show.html.twig', array('user' => $user));

    }

    /**
     * @Route("/user/remove={id}", name="user_remove")
     */
    public function remove(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $doctrine->getRepository(User::class)->find($id);

        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/user/update={id}", name="user_update")
     */
    public function update(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id);
        $form = $this->createForm(UserUpdateFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $em = $doctrine->getManager();
            $user = $form->getData();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'User updated!');
            return $this->redirectToRoute('user_list');
        }
        return $this->render('back_end/user/update.html.twig', [
            'userUpdate' => $form->createView()
        ]);
    }

    /**
     * @Route("/statsUsers", name="statsUsers")
     */
    public function statsUsers(): Response
    {
        $Users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $users = 0;
        $admins = 0;

        foreach ($Users as $User) {
            if ($User->getRoles()[0] == 'ROLE_USER') {
                $users++;

            } else if ($User->getRoles()[0] == 'ROLE_ADMIN') {
                $admins++;
            }
        }

        return $this->render('back_end/user/stats_users.html.twig', [
            'controller_name' => 'StatsUsersController',
            'users' => $users, 'admins' => $admins
        ]);
    }
}
