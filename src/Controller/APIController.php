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
     * @Route("/card/api/deck",  name="card-api-deck")
     */
    public function deck(SessionInterface $session): Response
    {
        $deck = $session->get("deck") ?? null;
        $cards = $deck->getCards();
        $returnArray = [];
        foreach ($cards as $card) {
            $cardArray = [];
            $cardArray[] = $card->getRank();
            $cardArray[] = $card->getSuit();
            $returnArray[] = $cardArray;
        }
        $jsonDeck = json_encode($returnArray);
        return new JsonResponse($jsonDeck);
    }
}
