<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class APIController extends AbstractController
{
    /**
     * @Route("/card/api/deck",  name="api-deck")
     */
    public function deck(SessionInterface $session): Response
    {
        $deck = $session->get("deck") ?? null;
        if ($deck == null) {
            $deck = new \App\Card\Deck();
            $session->set("deck", $deck);
        }
        $cards =  $deck->getCards();
        $jsonDeck = [];
        foreach ($cards as $card) {
            $cardArray = ["suit" => "{$card->getSuit()}", "rank" => "{$card->getRank()}"];
            $jsonDeck[] = $cardArray;
        }
        $data = [
            'deck' => $jsonDeck,
        ];
    
        return new JsonResponse($data);
    }

    /**
     * @Route("/card/api/deck/shuffle",  name="api-shuffle", methods={"POST", "GET"})
     */
    public function shuffle(SessionInterface $session): Response
    {
        $deck = $session->get("deck") ?? null;
        $deck->shuffleDeck();
        $cards =  $deck->getCards();
        $jsonDeck = [];
        foreach ($cards as $card) {
            $cardArray = ["suit" => "{$card->getSuit()}", "rank" => "{$card->getRank()}"];
            $jsonDeck[] = $cardArray;
        }
        $data = [
            'deck' => $jsonDeck,
        ];
    
        return new JsonResponse($data);
    }
}
