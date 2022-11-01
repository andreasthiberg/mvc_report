<?php

namespace App\Card;

use App\Card\Card;

class Deck
{
    /**
     * @var array<Card> collection of cards in deck
    */
    protected array $cards = [];

    public function __construct()
    {
        $suits = ["Clubs", "Diamonds","Hearts","Spades"];
        for ($i = 0; $i < 4; $i++) {
            for ($j = 1; $j < 14; $j++) {
                $this->addCard($suits[$i], $j);
            }
        }
    }

    /**
     *
     * Gets all cards
     *
     * @return array<Card>
    */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     *
     * Gets all cards sorted by suit and rank
     *
     * @return array<Card>
    */
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

    public function addCard(string $suit, int $rank): void
    {
        $this->cards[] = new Card($suit, $rank);
    }

    public function shuffleDeck(): void
    {
        shuffle($this->cards);
    }

    /**
     *
     * Draws a number of cards and returns them
     *
     * @return array<Mixed>
    */
    public function drawCards(int $amount = 1)
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
