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
        $cardString = $this->rank." of ".$this->suit;
        return $cardString;
    }
}