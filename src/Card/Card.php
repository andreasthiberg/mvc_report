<?php

namespace App\Card;

class Card
{
    private string $suit ;
    private int $rank;
    private string $color;

    public function __construct(string $suit, int $rank)
    {
        $this->suit = $suit;
        $this->rank = $rank;
        $this->color = "black";
        if ($suit == "Hearts" || $suit == "Diamonds") {
            $this->color = "red";
        } elseif ($suit == "Joker") {
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

    public function getRankStringIncludingJoker(): string
    {
        if ($this->rank == "0") {
            return "Jo";
        } else if (($this->rank == "13")){
            return "K";
        } else if (($this->rank == "12")){
            return "Q";
        } else if (($this->rank == "11")){
            return "J";
        } else if (($this->rank == "1")){
            return "A";
        }
        return strval($this->rank);
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function getRank(): int
    {
        return $this->rank;
    }

    public function getColor(): string
    {
        return $this->color;
    }
}
