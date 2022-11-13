<?php

namespace App\Card;

/**
 * Class representing a single playing card
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

    /**
     * Returns a symbol representing the suit of the card
     * @return string
     */
    public function getSymbol()
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

    /**
     * Returns a string representation of the card rank, with king as "K" etc.
     * @return string
     */
    public function getRankStringIncludingJoker()
    {
        $rankStringArray = array(
            "0" => "Jo",
            "13" => "K",
            "12" => "Q",
            "11" => "J",
            "1" => "A",
            "14" => "A",
        );

        if (array_key_exists(strval($this->rank), $rankStringArray)) {
            return $rankStringArray[strval($this->rank)];
        } else {
            return strval($this->rank);
        }
    }

    /**
     * Returns card suit.
     * @return string
     */
    public function getSuit()
    {
        return $this->suit;
    }

    /**
     * Returns card rank.
     * @return int
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Returns card color.
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }
}
