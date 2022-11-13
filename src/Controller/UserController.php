<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/proj/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/user-home.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("proj/user/create", name="user-create", methods={"GET"})
     */
    public function createUser(): Response
    {
        return $this->render('user/user-create.html.twig');
    }

    /**
     * @Route("proj/user/create", name="user-create-handler", methods={"POST"})
     */
    public function createUserHandler(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $entityManager = $doctrine->getManager();

        // Create new user from post request
        $user = new User();

        $user->setName($request->request->get('name'));
        $user->setEmail($request->request->get('email'));
        $user->setPassword($request->request->get('password'));
        $user->setAkronym($request->request->get('akronym'));
        $user->setType($request->request->get('type'));

        $entityManager->persist($user);

        $entityManager->flush();

        return $this->redirectToRoute('user-home');
    }

    /**
     * @Route("proj/user/login", name="user-login", methods={"GET"})
     */
    public function loginUser(): Response
    {
        return $this->render('user/user-login.html.twig');
    }

    /**
     * @Route("proj/user/login", name="user-login-handler", methods={"POST"})
     */
    public function loginUserHandler(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $entityManager = $doctrine->getManager();

        // Create new user from post request
        $user = new User();

        $email = $request->request->get('email');
        $user = $request->request->get('password');

        $entityManager->persist($user);

        $entityManager->flush();

        return $this->redirectToRoute('user-home');
    }

}
