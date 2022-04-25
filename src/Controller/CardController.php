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
     * @Route("/card/deck/")
     * name="card-deck"
     */
    public function deck(SessionInterface $session): Response
    {
        /* Get deck from session or create one*/
        $deck = $session->get("deck") ?? null;
        if ($deck == null){
            $deck = new \App\Card\Deck(); 
            $session->set("deck",$deck);
        }

        $data = [
            'cards_as_strings' => $deck->cardsAsStrings()
        ];
        return $this->render('card/deck.html.twig', $data);
    }

    /**
     * @Route("/card/deck2/",name="card-deck")
     */
    public function deck2(SessionInterface $session): Response
    {
        /* Get deck from session or create one*/
        $deck = new \App\Card\DeckWith2Jokers(); 
        $session->set("deck",$deck);
        $data = [
            'cards_as_strings' => $deck->cardsAsStrings()
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
        $session->set("deck",$deck);
        $deck->shuffleDeck();
        $data = [
            'cards_as_strings' => $deck->cardsAsStrings()
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
        if ($deck == null){
            $deck = new \App\Card\Deck(); 
            $session->set("deck",$deck);
        }
        
        /*Create string representing drawn card */
        if($deck->getNumberOfCards() == 0 ){
            $drawnCardString = "The deck is empty.";
        } else {
            $drawnCard = $deck->drawCards(1);
            $drawnCardString[] = $drawnCard[0]->getAsString();
        }

        $data = [
            'drawn_cards_strings' => $drawnCardString,
            'cards_left' => $deck->getNumberOfCards()
        ];

        return $this->render('card/draw.html.twig', $data);
    }

    /**
     * @Route("/card/deck/draw/{amount}",name="card-draw-amount")
     */
    public function draw_number(SessionInterface $session, int $amount): Response
    {   
        /* Get deck from session or create one*/
        $deck = $session->get("deck") ?? null;
        if ($deck == null){
            $deck = new \App\Card\Deck(); 
            $session->set("deck",$deck);
        }

        /* Create string array representing drawn cards */
        $drawnCards = $deck->drawCards($amount);
        $drawnCardsAsStrings = [];

        if(empty($drawnCards)){
            $drawnCardsAsStrings[] = "The deck is empty.";
        } else {
            foreach($drawnCards as $card){
                $drawnCardsAsStrings[] = $card->getAsString();
            }
        }

        $data = [
            'drawn_cards_strings' => $drawnCardsAsStrings,
            'cards_left' => $deck->getNumberOfCards()
        ];

        return $this->render('card/draw.html.twig', $data);
    }

    /**
     * @Route("/card/deck/deal/{players}/{cards}",name="cards-to-players")
     */
    public function cards_to_players(
        SessionInterface $session,
        int $players, int $cards): Response
    {   
        /* Get deck from session or create one*/
        $deck = $session->get("deck") ?? null;
        if ($deck == null){
            $deck = new \App\Card\Deck(); 
            $session->set("deck",$deck);
        }

        /* Add players and give them cards from the deck */
        $playerArray = [];
        for($i = 1; $i<($players+1); $i++){
            $newPlayer = new \App\Card\Player();
            $newPlayer->setNumber($i);
            $drawnCards = $deck->drawCards($cards);
            foreach ($drawnCards as $card){
                $newPlayer->giveCard($card);
            }
            $playerArray[] = $newPlayer;
        }
        
        /*Create string representations of players and their cards, stored in Array */
        $playersRepresentation = [];
        foreach($playerArray as $player){
            $playerStringArray = [];
            $playerStringArray[] = $player->getPlayerName();
            $playerStringArray[] = $player->getHandAsStrings();
            $playersRepresentation[] = $playerStringArray;
        }

        $cardsRemaining = $deck->getNumberOfCards();
        
        $data = [
            'players' => $playersRepresentation,
            'cards_left' => $cardsRemaining
        ];

        return $this->render('card/players.html.twig', $data);
    }
}
