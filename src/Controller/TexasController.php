<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Card\TexasGame;

class TexasController extends AbstractController
{
    /**
     * @Route("/proj", name="texas-home")
     */
    public function texasHome(): Response
    {
        return $this->render('texas/texas-home.html.twig');
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

        //Session variabel that makes sure new hand is dealt after hand is over
        $session->set("hand_over", false);

        //Get player's money.
        $currentMoney = $session->get("current_money") ?? null;

        //Session variable that decides if game is over (when money reaches 0)
        $session->set("game_over", false);
        if ($currentMoney == 0) {
            $session->set("game_over", true);
        }

        //Initialize money if not yet done
        if ($currentMoney == null && $currentMoney != null) {
            $currentMoney = 200;
            $session->set("current_money", $currentMoney);
        }

        // Deal initial cards if not already dealt
        if (count($game->getUserCards()) == 0) {
            $game->dealHands();
        }

        // Initialize empty strings
        $winnerString = "";
        $betString = "";
        $userCombination = "";
        $bankCombination = "";

        // Get current bet amount
        $currentBet = $game->getCurrentBet();

        // If all cards are on the table, end the game and get the result
        if (count($game->getTableCards()) == 5) {
            $session->set("hand_over", true);
            $gameResult = $game->endGame();
            if ($gameResult["winner"] == 0) {
                $winnerString = "Ingen vinnare!";
                $betString = "Du får tillbaka dina marker.";
                $session->set("current_money", $currentMoney + $currentBet);
            } elseif ($gameResult["winner"] == 1) {
                $winnerString = "Banken vinner!";
                $betString = "Du förlorade " . $currentBet . " marker.";
            } elseif ($gameResult["winner"] == 2) {
                $winnerString = "Du vann!";
                $betString = "Du vann " . ($currentBet * 2) . " marker!";
                $newTotalMoney = $currentMoney + ($currentBet * 2);
                $session->set("current_money", $newTotalMoney);
            }

            if ($gameResult["won_by_rank"]) {
                $winnerString .= " Handen avgjordes genom kortvalör.";
            }

            $bankCombination = "Banken har " . $gameResult["first_player_combination"] . ".";
            $userCombination = "Du har " . $gameResult["second_player_combination"] . ".";
        }


        // Send data to template
        $userCards = $game->getUserCards();
        $bankCards = $game->getBankCards();
        $tableCards = $game->getTableCards();

        $data = [
            'user_cards' => $userCards,
            'bank_cards' => $bankCards,
            'table_cards' => $tableCards,
            'winner_string' => $winnerString,
            'bank_combination' => $bankCombination,
            'user_combination' => $userCombination,
            'current_money' => $session->get("current_money"),
            'current_bet' => $game->getCurrentBet(),
            'hand_over' => $session->get("hand_over"),
            'game_over' => $session->get("game_over"),
            'bet_string' => $betString
        ];

        return $this->render('texas/texas-play.html.twig', $data);
    }

    /**
     * Deals with POST request - different game actions
     *
     * @Route("/proj/play", name="texas-handler", methods={"POST"})
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function texasAction(SessionInterface $session, Request $request): Response
    {

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
                $currentMoney = $session->get("current_money");
                $session->set("current_money", $currentMoney - 20);
        } elseif ($action == "restart") {
            $session->set("current_money", 200);
            $newGame = new TexasGame();
            $session->set("texas_game", $newGame);
        }

        return $this->redirectToRoute('texas-play');
    }
}
