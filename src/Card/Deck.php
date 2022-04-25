<?php

namespace App\Card;

use App\Card\Card;

class Deck
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
    }

    public function addCard($suit,$rank){
        $this->deck[] = new Card($suit,$rank);
    }

    public function cardsAsStrings(): array {
        $stringArray = [];
        foreach ($this->deck as $card){
            $stringArray[] = $card->getAsString();
        }
        return $stringArray;
    }
}