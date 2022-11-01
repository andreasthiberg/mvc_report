<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\Game;
use App\Card\DeckWith2Jokers;
use App\Card\Deck;
use App\Card\Player;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GameController extends AbstractController
{
    /**
     * Route for 21 game home page
     *
     *
     * @Route("/game", name="game-home")
     */
    public function gameHome(): Response
    {
        return $this->render('game/game-home.html.twig');
    }

    /**
     * Route for game documentation
     *
     *
     * @Route("/game/doc", name="game-doc")
     */
    public function gameDoc(): Response
    {
        return $this->render('game/game-doc.html.twig');
    }

    /**
     * Route to play 21 card game
     *
     * @Route("/game/play", name="game-play", methods={"GET"}))
     */
    public function gamePlay(SessionInterface $session): Response
    {
        /**
         * @var Game Current game
        */
        $game = $session->get("game") ?? null;

        if ($game == null) {
            $game = new Game();
            $session->set("game", $game);
        }

        $userCards = $game->getUserCards();
        $bankCards = $game->getBankCards();
        $bankPoints = $game->getBankPoints();
        $userPoints = $game->getUserPoints();
        $turn = $game->getTurn();
        $winner = $game->getWinner();

        $data = [
            'user_cards' => $userCards,
            'bank_cards' => $bankCards,
            'bank_points' => $bankPoints,
            'user_points' => $userPoints,
            'turn' => $turn,
            'winner' => $winner
        ];

        return $this->render('game/game-play.html.twig', $data);
    }

    /**
     * POST - draw a card for player or bank
     *
     * @Route("/game/play", name="game-handler", methods={"POST"})
     */
    public function gameAction(SessionInterface $session, Request $request): Response
    {
        /**
         * @var Game Current game
        */

        $game = $session->get("game");
        $action = $request->request->get('action');
        if ($action == "user-draw") {
            $game->drawUserCard();
        } elseif ($action == "bank-draw") {
            $game->drawBankCard();
        } elseif ($action == "restart") {
            $game = new Game();
            $session->set("game", $game);
        } elseif ($action == "user-stop") {
            $game->userStop();
        }

        return $this->redirectToRoute('game-play');
    }
}
