<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

        $hashedPassword = password_hash($request->request->get('password'), PASSWORD_DEFAULT);

        $user->setName($request->request->get('name'));
        $user->setEmail($request->request->get('email'));
        $user->setPassword($hashedPassword);
        $user->setAkronym($request->request->get('akronym'));
        $user->setType($request->request->get('type'));
        $user->setPicture($request->request->get('picture'));

        $entityManager->persist($user);

        $entityManager->flush();

        return $this->redirectToRoute('texas-home');
    }

    /**
     * @Route("proj/user/login/{message}", name="user-login", methods={"GET"})
     */
    public function loginUser(string $message = ""): Response
    {
        return $this->render('user/user-login.html.twig', ["message" => $message]);
    }

    /**
     * @Route("proj/user/login", name="user-login-handler", methods={"POST"})
     */
    public function loginUserHandler(
        UserRepository $userRepository,
        ManagerRegistry $doctrine,
        Request $request
    ): Response {


        $akronym = $request->request->get('akronym');
        $password = $request->request->get('password');

        $user = $userRepository
        ->findOneBy(array('akronym' => $akronym));

        if($user != null){
            if(password_verify($password,$user->getPassword())){
                return $this->redirectToRoute('user-page', ["userId" => $user->getId()]);
            }
        }
        return $this->redirectToRoute('user-login', ["message" => "FEL!"]);

    }

    /**
     * @Route("proj/reset", name="user-reset-handler", methods={"GET"})
     */
    public function resetDatabase(
        ManagerRegistry $doctrine,
        UserRepository $userRepository
    ): Response {
        
        $entityManager = $doctrine->getManager();

        $oldUsers = $userRepository
        ->findAll();

        foreach ($oldUsers as $oldUser) {
            $entityManager->remove($oldUser);
        }
        $entityManager->flush();

        // Create standard normal user
        $user = new User();
        $hashedPassword = password_hash("doe", PASSWORD_DEFAULT);

        $user->setName("Doeman Doeson");
        $user->setEmail('doe@email.com');
        $user->setPassword($hashedPassword);
        $user->setAkronym("doe");
        $user->setType("normal");
        $user->setPicture("https://freesvg.org/img/Male-Avatar-2.png");

        $entityManager->persist($user);

        $entityManager->flush();

        // Create standard admin user
        $user2 = new User();
        $hashedPassword = password_hash("admin", PASSWORD_DEFAULT);

        $user2->setName("Admin Adminson");
        $user2->setEmail('admin@email.com');
        $user2->setPassword($hashedPassword);
        $user2->setAkronym("admin");
        $user2->setType("admin");
        $user2->setPicture("https://freesvg.org/img/Female-Avatar-2.png");

        $entityManager->persist($user2);

        $entityManager->flush();

        return $this->redirectToRoute('texas-home');
    }

    /**
     * @Route("proj/user/show-all", name="user-show-all", methods={"GET"})
     */
    public function showAllUsers(
        UserRepository $userRepository
    ): Response {
        $users = $userRepository
            ->findAll();

        $data = [
            'users' => $users
        ];

        return $this->render('user/user-show-all.html.twig', $data);
    }

    /**
     * @Route("proj/user/{userId}", name="user-page", methods={"GET"})
     */
    public function userPage(
        UserRepository $userRepository,
        int $userId
    ): Response {
        $user = $userRepository
        ->find($userId);

        $data = [
            'user' => $user
        ];

        return $this->render('user/user-page.html.twig', $data);
    }


}
