<?php

namespace App\Card;

use App\Card\Card;
use App\Card\Deck;

class DeckWithAcesHigh extends Deck
{
    public function __construct()
    {
        $suits = ["Clubs", "Diamonds","Hearts","Spades"];
        for ($i = 0; $i < 4; $i++) {
            for ($j = 2; $j < 15; $j++) {
                $this->addCard($suits[$i], $j);
            }
        }
    }
}
