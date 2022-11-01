<?php

namespace App\Card;

class Card
{
    private $suit;
    private $rank;
    private $color;

    public function __construct($suit, $rank)
    {
        $this->suit = $suit;
        $this->rank = $rank;
        $this->color = "black";
        if($suit == "Hearts" || $suit == "Diamonds"){
            $this->color = "red";
        } else if ($suit == "Joker") {
            $this->color = "blue";
        }
    }

    public function getSymbol(): string
    {
        $suitSymbol = "♦";
        if ($this->suit == "Hearts") {
            $suitSymbol = "♥";
        }
        if ($this->suit == "Clubs") {
            $suitSymbol = "♣";
        }
        if ($this->suit == "Spades") {
            $suitSymbol = "♠";
        }
        if ($this->suit == "Joker") {
            $suitSymbol = "?";
        }
        return $suitSymbol;
    }

    public function getRankIncludingJoker(): string
    {
        if ($this->rank == "0") {
            return "J";
        } else {
            return $this->rank;
        }
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function getRank(): int
    {
        return $this->rank;
    }

    public function getColor()
    {
        return $this->color;
    }
}
