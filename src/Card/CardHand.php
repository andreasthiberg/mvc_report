<?php

namespace App\Card;

use App\Card\Card;

class CardHand
{
    private $hand = [];

    /* Adds a card to the hand */
    public function addCard(Card $newCard)
    {
        $this->hand[] = $newCard;
    }

    public function getCards()
    {
        return $this->hand;
    }
}
