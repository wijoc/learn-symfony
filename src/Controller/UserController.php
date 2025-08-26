<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\UserFormType as UserForm;
use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

final class UserController extends AbstractController
{
    #[Route('/admin/users', name: 'app_user')]
    public function index(Request $request, UsersRepository $userRepository): Response
    {
        /** Prepare Filter */
        $filters = [
            'orderBy' => $request->query->get('orderBy', 'firt_name'),
            'sort' => $request->query->get('sort', 'ASC')
        ];

        if ($request->query->get('s', '') !== "") {
            $filters['s'] = $request->query->get('s', '');
        }

        /** Get Data */
        $users = $userRepository->getUsers($filters);

        return $this->render('user/index.html.twig', [
            'userController' => 'UserController',
            'users' => $users
        ]);
    }

    #[Route('/admin/users/create', name: 'app_user_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $userEntity = new Users();
        $form = $this->createForm(UserForm::class, $userEntity);
        $form->handleRequest($request);

        /** Handle submission process */
        if ($form->isSubmitted() && $form->isValid()) {
            // Save entity
            // $em = $em->getDoctrine()->getManager();
            $em->persist($userEntity);
            $em->flush();

            $form = $this->createForm(UserForm::class);

            $this->addFlash('success', 'Form submitted!');

            /** Redirect page */
            return $this->redirectToRoute('app_user');
        }

        /** Show Form */
        return $this->render('user/form.html.twig', [
            // 'userController' => 'UserController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    public function edit(Request $request, EntityManagerInterface $em, Users $user): Response
    {
        // `$user` is automatically fetched by ParamConverter
        $form = $this->createForm(UserForm::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush(); // No need persist(), entity is already managed

            $this->addFlash('success', 'Form submitted!');

            /** Redirect page */
            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Edit User',
        ]);
    }

    /**
     * Delete using post form
     */
    #[Route('/admin/users/{id}/delete', name: 'app_user_delete', methods: ['POST'])]
    public function delete(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(Users::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('app_user_index');
    }

    /**
     * Delete using API or Ajax
     */
    // #[Route('/api/users/{id}', name: 'api_user_delete', methods: ['DELETE'])]
    // public function apiDelete(User $user, EntityManagerInterface $em): Response
    // {
    //     $em->remove($user);
    //     $em->flush();

    //     return new Response(null, 204);
    // }
}
