<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
     * @Route("/proj/play", name="texas-play")
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

        $userCards = $game->getUserCards();
        $bankCards = $game->getBankCards();
        $turn = $game->getTurn();

        $data = [
            'user_cards' => $userCards,
            'bank_cards' => $bankCards
        ];
        return $this->render('texas/texas-play.html.twig');
    }

    /**
     * Deals with POST request - different game actions
     *
     * @Route("/prok/play", name="texas-handler", methods={"POST"})
     */
    public function texasAction(SessionInterface $session, Request $request): Response
    {
        /**
         * @var TexasGame Current game
        */
        $game = $session->get("texas_game");

        $action = $request->request->get('action');
        if ($action == "user-draw") {
            $game->drawUserCard();
        } elseif ($action == "bank-draw") {
            $game->drawBankCard();
        } elseif ($action == "restart") {
            $game = new TexasGame();
            $session->set("texas_game", $game);
        } elseif ($action == "user-stop") {
            $game->userStop();
        }

        return $this->redirectToRoute('texas-play');
    }

}
