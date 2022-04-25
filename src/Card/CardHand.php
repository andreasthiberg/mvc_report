<?php

namespace App\Card;

use App\Card\Card;

class CardHand
{
    private $hand = [];

    /* Adds a card to the hand */
    public function addCard(Card $newCard){
        $this->hand[] = $newCard;
    }
    
    /* Returns an array of strings representing the cards in the hand */
    public function cardsAsStrings(): array {
        $cardStrings = [];
        foreach($this->hand as $card){
            $cardStrings[] = $card->getAsString();
        }
        return $cardStrings;
    }
}