<?php

namespace App\Card;

use App\Card\Card;
use App\Card\Deck;

class DeckWith2Jokers extends Deck
{
    private $deck = [];

    public function __construct()
    {
        $suits = ["Clubs", "Diamonds","Hearts","Spades"];
        for ($i = 0; $i < 4; $i++) {
            for ($j = 1; $j < 14; $j++) {
                $this->addCard($suits[$i],$j);
            }
        }
        $this->addCard("Joker",1);
        $this->addCard("Joker",2);
    }
}