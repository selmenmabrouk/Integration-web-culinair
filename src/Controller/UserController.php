<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use App\Form\UserUpdateFormType;
class UserController extends AbstractController

{
    /**
     * @Route("/admin/user/show={id}", name="user")
     */
    public function index(ManagerRegistry $doctrine, int $id): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id);

        return $this->render('admin/user/show.html.twig', array('user' =>$user));

    }
    /**
     * @Route("/admin/user/remove={id}", name="user_remove")
     */
    public function remove(ManagerRegistry $doctrine, int $id) : Response{
         $entityManager = $doctrine->getManager();
        $user = $doctrine->getRepository(User::class)->find($id);

        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('accueil');
    }
    /**
     * @Route("/admin/user/update={id}", name="user_update")
     */
    public function update(ManagerRegistry $doctrine, int $id, Request $request): Response {
        $user = $doctrine->getRepository(User::class)->find($id);
        $form = $this->createForm(UserUpdateFormType::class,$user);
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
        return $this->render('admin/user/update.html.twig', [
            'userUpdate' => $form->createView()
        ]);
    }
    /**
     * @Route("/admin/user/showAll", name="user_list")
     */
    public function list(ManagerRegistry $doctrine): Response {
        $users = $doctrine->getRepository(User::class)->findAll();
        return $this->render('/admin/user/showAll.html.twig', ['users' => $users]);
    }
}
