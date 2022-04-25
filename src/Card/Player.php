<?php

namespace App\Card;

use App\Card\CardHand;

class Player
{
    private $playerHand;

    public function __construct()
    {
        $this->playerHand = new CardHand();
    }

    public function giveCard(Card $newCard){
        $this->playerHand->addCard($newCard);
    }

    public function getHandAsStrings(): array {
        return $this->playerHand->cardsAsString();
    }
}