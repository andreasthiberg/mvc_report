<?php

namespace App\Card;

class Card
{
    private $suit;
    private $rank;

    public function __construct($suit,$rank)
    {
        $this->suit = $suit;
        $this->rank = $rank;
    }

    public function getAsString(): string {
        if($this->suit == "Joker"){
            return "Joker";
        }
        $cardString = $this->rank." of ".$this->suit;
        return $cardString;
    }

    public function getSuit(): string {
        return $this->$suit;
    }
}