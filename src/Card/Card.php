<?php

namespace App\Card;

class Card
{
    private string $suit;
    private int $rank;
    private string $color;

    public function __construct(string $suit, int $rank)
    {
        $this->suit = $suit;
        $this->rank = $rank;
        $this->color = "black";
    }

    public function getRankStringIncludingJoker(): string
    {
        if ($this->rank == "0") {
            return "Jo";
        } 
        return strval($this->rank);
    }

}
