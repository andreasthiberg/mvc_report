<?php

namespace App\Card;

/**
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
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
        $suitSymbolArray = array(
            "Hearts" => "♥",
            "Clubs" => "♣",
            "Spades" => "♠",
            "Joker" => "?",
            "Diamonds" => "♦",
        );

        return $suitSymbolArray[$this->suit];
    }

    public function getRankStringIncludingJoker(): string
    {
        $rankStringArray = array(
            "0" => "Jo",
            "13" => "K",
            "12" => "Q",
            "11" => "J",
            "1" => "A",
        );

        if (array_key_exists(strval($this->rank), $rankStringArray)) {
            return $rankStringArray[strval($this->rank)];
        } else {
            return strval($this->rank);
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

    public function getColor(): string
    {
        return $this->color;
    }
}
