<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Card\TexasGame;
use App\Repository\UserRepository;

class TexasController extends AbstractController
{
    /**
     * @Route("/proj", name="texas-home")
     */
    public function texasHome(SessionInterface $session): Response
    {
        $user = $session->get("logged_in_user");
        $data = [ 
            "logged_in_user" => $user
        ];
        return $this->render('texas/texas-home.html.twig', $data);
    }

    /**
     * @Route("/proj/about", name="texas-about")
     */
    public function texasAbout(): Response
    {
        return $this->render('texas/texas-about.html.twig');
    }

    /**
     * @Route("/proj/play", name="texas-play",  methods={"GET"})
     */
    public function texasPlay(SessionInterface $session): Response
    {
        /**
         * @var TexasGame Current game of texas hold'em
        */
        $game = $session->get("texas_game") ?? null;
        if ($game == null) {
            $game = new TexasGame();
            $session->set("texas_game", $game);
        }

        //Get player's money.
        $logged_in_user = $session->get("logged_in_user");
        $currentMoney = $logged_in_user->getChips();

        $session->set("game_over", false);
        //Ends game if money is 0 and hand is over

        if ($currentMoney == 0 && $session->get("hand_over")) {
            $session->set("game_over", true);
        }

        // Deal initial cards if not already dealt
        if (count($game->getUserCards()) == 0) {
            $game->dealHands();
        }

        $session->set("hand_over", false);

        // If all cards are on the table, end the game and get the result
        if (count($game->getTableCards()) == 5) {
            $session->set("hand_over", true);
            $gameResult = $game->endGame($currentMoney);
            $logged_in_user->setChips($gameResult["current_money"]);

            $bankCombination = "Banken har " . $gameResult["first_player_combination"] . ".";
            $userCombination = "Du har " . $gameResult["second_player_combination"] . ".";

            if ($gameResult["current_money"] == 0) {
                $gameLost = true;
            }
        }


        // Send data to template
        $data = [
            'user_cards' => $game->getUserCards(),
            'bank_cards' => $game->getBankCards(),
            'table_cards' => $game->getTableCards(),
            'winner_string' => $gameResult["winner_string"] ?? "",
            'bank_combination' => $bankCombination ?? "",
            'user_combination' => $userCombination ?? "",
            'current_money' => $logged_in_user->getChips(),
            'current_bet' => $game->getCurrentBet(),
            'hand_over' => $session->get("hand_over") ?? false,
            'game_over' => $session->get("game_over") ?? false,
            'bet_string' => $gameResult["bet_string"] ?? "",
            "game_lost" => $gameLost ?? false
        ];

        return $this->render('texas/texas-play.html.twig', $data);
    }

    /**
     * Deals with POST request - different game actions
     *
     * @Route("/proj/play", name="texas-handler", methods={"POST"})
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function texasAction(SessionInterface $session, Request $request, 
    UserRepository $userRepository,ManagerRegistry $doctrine): Response
    {
        
        //Update database with current chips
        $entityManager = $doctrine->getManager();

        $user = $userRepository
        ->find($session->get("logged_in_user")->getid());
        $user->setChips($session->get("logged_in_user")->getChips());
        $entityManager->persist($user);
        $entityManager->flush();


        /**
         * @var TexasGame Current game
        */
        $game = $session->get("texas_game");

        $action = $request->request->get('action');
        if ($action == "new-cards") {
            if ($session->get("hand_over")) {
                $newGame = new TexasGame();
                $session->set("texas_game", $newGame);
            } else {
                $game->dealTableCards();
            }
        } elseif ($action == "bet") {
                $game->makeBet();
                $logged_in_user = $session->get("logged_in_user");
                $logged_in_user->setChips($logged_in_user->getChips() - 20);
        }  elseif ($action == "new-hand") {
            $newGame = new TexasGame();
            $session->set("texas_game", $newGame);
        }

        return $this->redirectToRoute('texas-play');
    }
}
