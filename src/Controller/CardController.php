<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    /**
     * @Route("/card")
     * name="card-home"
     */
    public function card(): Response
    {
        return $this->render('card/card.html.twig');
    }

    /**
     * @Route("/card/deck/")
     */
    public function report(): Response
    {
        $deck = new \App\Card\Deck();
        $data = [
            'cards_as_strings' => $deck->cardsAsStrings()
        ];
        return $this->render('card/deck.html.twig', $data);
    }

    /**
     * @Route("/card/deck/shuffle")
     */
    public function about(): Response
    {
        return $this->render('card/shuffle.html.twig');
    }
}
