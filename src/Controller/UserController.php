<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
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

        $user->setPassword($hashedPassword);
        $user->setChips(500);
        $user->setType("normal");
        $user->setAkronym($request->request->get('akronym'));

        $entityManager->persist($user);

        $entityManager->flush();

        return $this->redirectToRoute('texas-home');
    }

    /**
     * @Route("proj/user/login", name="user-login", methods={"GET"})
     */
    public function loginUser(SessionInterface $session): Response
    {
        $message = $session->get("login_message");
        $session->set("login_message", "");
        return $this->render('user/user-login.html.twig', ["message" => $message]);
    }

    /**
     * @Route("proj/user/login", name="user-login-handler", methods={"POST"})
     */
    public function loginUserHandler(
        UserRepository $userRepository,
        Request $request,
        SessionInterface $session
    ): Response {


        $akronym = $request->request->get('akronym');
        $password = $request->request->get('password');

        $user = $userRepository
        ->findOneBy(array('akronym' => $akronym));

        if ($user != null) {
            if (password_verify($password, $user->getPassword())) {
                $session->set("logged_in_user", $user);
                return $this->redirectToRoute('user-page', ["userId" => $user->getId()]);
            }
        }
        $session->set("login_message", "Inloggningen misslyckades");
        return $this->redirectToRoute('user-login');
    }

    /**
     * @Route("proj/user/logout", name="user-logout")
     */
    public function logoutUser(SessionInterface $session): Response
    {

        $session->set("logged_in_user", null);
        return $this->redirectToRoute('texas-home');
    }

    /**
     * @Route("proj/reset", name="user-reset-handler", methods={"GET"})
     */
    public function resetDatabase(
        ManagerRegistry $doctrine,
        UserRepository $userRepository,
        SessionInterface $session
    ): Response {

        $session->set("logged_in_user", null);
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
        $user->setChips(500);
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
        $user2->setChips(10000);
        $user2->setPicture("https://freesvg.org/img/Female-Avatar-2.png");

        $entityManager->persist($user2);

        $entityManager->flush();

        return $this->redirectToRoute('texas-home');
    }

    /**
     * @Route("proj/user/admin-page", name="user-admin-page", methods={"GET"})
     */
    public function adminPage(
        UserRepository $userRepository,
        SessionInterface $session
    ): Response {

        $loggedInUser = $session->get("logged_in_user");

        if ($loggedInUser == null) {
            return $this->render('user/user-admin-page.html.twig', ["admin_access" => false]);
        } elseif ($loggedInUser->getType() != "admin") {
            return $this->render('user/user-admin-page.html.twig', ["admin_access" => false]);
        }
        $users = $userRepository
            ->findAll();

        $data = [
            'admin_access' => true,
            'users' => $users
        ];

        return $this->render('user/user-admin-page.html.twig', $data);
    }

    /**
     * @Route("proj/user/{userId}", name="user-page", methods={"GET"})
     */
    public function userPage(
        UserRepository $userRepository,
        int $userId,
        SessionInterface $session
    ): Response {
        $loggedInUser = $session->get("logged_in_user");
        $user = $userRepository
        ->find($userId);

        $data = [
            'user' => $user,
            'logged_in_user' => $loggedInUser
        ];

        return $this->render('user/user-page.html.twig', $data);
    }

    /**
     * @Route("proj/user/update/{userId}", name="user-update", methods={"GET"})
     */
    public function userUpdate(
        UserRepository $userRepository,
        int $userId,
        SessionInterface $session
    ): Response {
        $loggedInUser = $session->get("logged_in_user");
        $user = $userRepository
        ->find($userId);

        $data = [
            'user' => $user,
            'logged_in_user' => $loggedInUser
        ];

        return $this->render('user/user-update.html.twig', $data);
    }

    /**
     * @Route("proj/user/update/{userId}", name="user-update-handler", methods={"POST"})
     */
    public function userUpdateHandler(
        UserRepository $userRepository,
        int $userId,
        Request $request,
        ManagerRegistry $doctrine,
    ): Response {

        $entityManager = $doctrine->getManager();

        $action = $request->request->get('action');
        $user = $userRepository
        ->find($userId);

        if ($action == "update") {
            $user->setName($request->request->get('name'));
            $user->setEmail($request->request->get('email'));
            $user->setPicture($request->request->get('picture'));
            $user->setChips($request->request->get('chips'));
            $entityManager->persist($user);
        } elseif ($action == "delete" && $user->getType() != "admin") {
            $entityManager->remove($user);
        }

        $entityManager->flush();

        return $this->redirectToRoute('texas-home');
    }
}
