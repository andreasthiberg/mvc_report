<?php

namespace App\Card;

use App\Card\Card;

class Deck
{
    protected $cards = [];

    public function __construct()
    {
        $suits = ["Clubs", "Diamonds","Hearts","Spades"];
        for ($i = 0; $i < 4; $i++) {
            for ($j = 1; $j < 14; $j++) {
                $this->addCard($suits[$i], $j);
            }
        }
    }

    public function getCards()
    {
        return $this->cards;
    }

    public function getSortedCards()
    {
        $sortedCardArray = array_merge(array(), $this->cards);
        usort($sortedCardArray, function ($cardA, $cardB) {
            if ($cardA->getSuit() == $cardB->getSuit()) {
                return($cardA->getRank() - $cardB->getRank());
            }
            return(strcmp($cardA->getSuit(), $cardB->getSuit()));
        });
        return $sortedCardArray;
    }

    public function addCard($suit, $rank)
    {
        $this->cards[] = new Card($suit, $rank);
    }

    public function shuffleDeck()
    {
        shuffle($this->cards);
    }

    public function drawCards($amount = 1): array
    {
        $drawnCards = [];
        for ($i = 0; $i < $amount; $i++) {
            if ($this->getNumberOfCards() != 0) {
                $drawnCards[] = array_pop($this->cards);
            }
        }
        return $drawnCards;
    }

    public function getNumberOfCards(): int
    {
        return count($this->cards);
    }
}
