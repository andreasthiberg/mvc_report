<?php

namespace App\Controller;

use App\Card\Card;
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
     * @Route("/game", name="game-home")
     */
    public function gameHome(): Response
    {
        return $this->render('game/game-home.html.twig');
    }

    /**
     * @Route("/game/play", name="game-play")
     */
    public function gamePlay(): Response
    {
        return $this->render('game/game-home.html.twig');
    }
}
