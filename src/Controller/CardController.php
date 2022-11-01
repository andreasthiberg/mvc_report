<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
     * @Route("/card/deck/", name="card-deck")
     */
    public function deck(SessionInterface $session): Response
    {
        /* Get deck from session or create one*/
        $deck = $session->get("deck") ?? null;
        if ($deck == null) {
            $deck = new \App\Card\Deck();
            $session->set("deck", $deck);
        }
        $cards = $deck->getSortedCards();
        $data = [
            'cards' => $cards
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    /**
     * @Route("/card/deck2/",name="card-deck-2")
     */
    public function deck2(SessionInterface $session): Response
    {
        /* Get deck from session or create one*/
        $deck = new \App\Card\DeckWith2Jokers();
        $session->set("deck", $deck);
        $data = [
            'cards' => $deck->getSortedCards()
        ];
        return $this->render('card/deck.html.twig', $data);
    }

    /**
     * @Route("/card/deck/shuffle",name="card-shuffle")
     */
    public function shuffle(SessionInterface $session): Response
    {
        /* Create new deck and shuffle it */
        $deck = new \App\Card\Deck();
        $session->set("deck", $deck);
        $deck->shuffleDeck();
        $data = [
            'cards' => $deck->getCards()
        ];
        return $this->render('card/shuffle.html.twig', $data);
    }

    /**
     * @Route("/card/deck/draw",name="card-draw")
     */
    public function draw(SessionInterface $session): Response
    {
        /* Get deck from session or create one*/
        $deck = $session->get("deck") ?? null;
        if ($deck == null) {
            $deck = new \App\Card\Deck();
            $session->set("deck", $deck);
        }


        $drawnCards = $deck->drawCards(1);


        $data = [
            'drawn_cards' => $drawnCards,
            'cards_left' => $deck->getNumberOfCards()
        ];

        return $this->render('card/draw.html.twig', $data);
    }

    /**
     * @Route("/card/deck/draw/{amount}",name="card-draw-amount", methods={"GET"})
     */
    public function drawAmount(SessionInterface $session, int $amount): Response
    {
        /* Get deck from session or create one*/
        $deck = $session->get("deck") ?? null;
        if ($deck == null) {
            $deck = new \App\Card\Deck();
            $session->set("deck", $deck);
        }

        /* Create string array representing drawn cards */
        $drawnCards = $deck->drawCards($amount);

        $data = [
            'drawn_cards' => $drawnCards,
            'cards_left' => $deck->getNumberOfCards()
        ];

        return $this->render('card/drawAmount.html.twig', $data);
    }

    /**
     * @Route("/card/deck/draw/{amount}",name="card-draw-amount-handler", methods={"POST"})
     */
    public function drawHandler(Request $request): Response
    {
        //Get number of cards to draw from post request
        $amount = $request->request->get('amount');

        return $this->redirectToRoute("card-draw-amount", ['amount' => $amount]);
    }

    /**
     * @Route("/card/deck/deal/{players}/{cards}",name="deal",  methods={"GET"})
     */
    public function dealCards(
        SessionInterface $session,
        int $players,
        int $cards
    ): Response 
        {
        /* Get deck from session or create one */
        $deck = $session->get("deck") ?? null;
        if ($deck == null) {
            $deck = new \App\Card\Deck();
            $session->set("deck", $deck);
        }

        /* Add players and give them cards from the deck */
        $playerArray = [];
        for ($i = 1; $i < ($players + 1); $i++) {
            $newPlayer = new \App\Card\Player();
            $newPlayer->setNumber($i);
            $drawnCards = $deck->drawCards($cards);
            foreach ($drawnCards as $card) {
                $newPlayer->giveCard($card);
            }
            $playerArray[] = $newPlayer;
        }

        $cardsRemaining = $deck->getNumberOfCards();

        $data = [
            'players' => $playerArray,
            'cards_left' => $cardsRemaining
        ];

        return $this->render('card/players.html.twig', $data);
    }
    
    /**
     * @Route("/card/deck/deal/{players}/{cards}",name="deal-handler", methods={"POST"})
     */
    public function dealHandler(Request $request): Response
    {
        //Get number of players and cards to draw from post request
        $players = $request->request->get('players');
        $cards = $request->request->get('cards');

        return $this->redirectToRoute("deal", ['cards' => $cards, 'players' => $players]);
    }

}
