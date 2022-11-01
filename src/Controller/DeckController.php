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

class DeckController extends AbstractController
{
    /**
     * @Route("/card", name="deck-home")
     */
    public function card(): Response
    {
        return $this->render('deck/deck-home.html.twig');
    }

    /**
     * @Route("/card/deck/", name="deck-create")
     */
    public function deck(SessionInterface $session): Response
    {
        /* Get deck from session or create one*/

        /**
        * @var Deck current card deck
        */
        $deck = $session->get("deck") ?? [];

        if ($deck == []) {
            $deck = new Deck();
            $session->set("deck", $deck);
        }
        $cards = $deck->getSortedCards();
        $data = [
            'cards' => $cards
        ];

        return $this->render('deck/deck.html.twig', $data);
    }

    /**
     * @Route("/card/deck2/",name="deck-create-2")
     */
    public function deck2(SessionInterface $session): Response
    {
        /* Get deck from session or create one*/
        $deck = new DeckWith2Jokers();
        $session->set("deck", $deck);
        $data = [
            'cards' => $deck->getSortedCards()
        ];
        return $this->render('deck/deck.html.twig', $data);
    }

    /**
     * @Route("/card/deck/shuffle",name="deck-shuffle")
     */
    public function shuffle(SessionInterface $session): Response
    {
        /* Create new deck and shuffle it */
        $deck = new Deck();
        $session->set("deck", $deck);
        $deck->shuffleDeck();
        $data = [
            'cards' => $deck->getCards()
        ];
        return $this->render('deck/shuffle.html.twig', $data);
    }

    /**
     * @Route("/card/deck/draw",name="deck-draw")
     */
    public function draw(SessionInterface $session): Response
    {
        /* Get deck from session or create one*/

        /**
        * @var Deck current card deck
        */
        $deck = $session->get("deck") ?? null;

        if ($deck == null) {
            $deck = new Deck();
            $session->set("deck", $deck);
        }

        $drawnCards = $deck->drawCards(1);

        $data = [
            'drawn_cards' => $drawnCards,
            'cards_left' => $deck->getNumberOfCards()
        ];

        return $this->render('deck/draw.html.twig', $data);
    }

    /**
     * @Route("/card/deck/draw/{amount}",name="deck-draw-amount", methods={"GET"})
     */
    public function drawAmount(SessionInterface $session, int $amount): Response
    {
        /* Get deck from session or create one*/

        /**
        * @var Deck current card deck
        */
        $deck = $session->get("deck") ?? null;

        if ($deck == null) {
            $deck = new Deck();
            $session->set("deck", $deck);
        }

        /* Create string array representing drawn cards */
        $drawnCards = $deck->drawCards($amount);

        $data = [
            'drawn_cards' => $drawnCards,
            'cards_left' => $deck->getNumberOfCards()
        ];

        return $this->render('deck/drawAmount.html.twig', $data);
    }

    /**
     * @Route("/card/deck/draw/{amount}",name="deck-draw-amount-handler", methods={"POST"})
     */
    public function drawHandler(Request $request): Response
    {
        //Get number of cards to draw from post request
        $amount = $request->request->get('amount');

        return $this->redirectToRoute("deck-draw-amount", ['amount' => $amount]);
    }

    /**
     * @Route("/card/deck/deal/{players}/{cards}",name="deck-deal",  methods={"GET"})
     */
    public function dealCards(
        SessionInterface $session,
        int $players,
        int $cards
    ): Response {

        /* Get deck from session or create one */

        /**
        * @var Deck current card deck
        */
        $deck = $session->get("deck") ?? [];

        if ($deck == []) {
            $deck = new Deck();
            $session->set("deck", $deck);
        }

        /* Add players and give them cards from the deck */
        $playerArray = [];
        for ($i = 1; $i < ($players + 1); $i++) {
            $newPlayer = new Player($i);

            /**
            * @var array<Card> current card deck
            */
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

        return $this->render('deck/deal.html.twig', $data);
    }

    /**
     * @Route("/card/deck/deal/{players}/{cards}",name="deck-deal-handler", methods={"POST"})
     */
    public function dealHandler(Request $request): Response
    {
        //Get number of players and cards to draw from post request
        $players = $request->request->get('players');
        $cards = $request->request->get('cards');

        return $this->redirectToRoute("deck-deal", ['cards' => $cards, 'players' => $players]);
    }
}
