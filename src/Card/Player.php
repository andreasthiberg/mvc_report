<?php

namespace App\Card;

use App\Card\CardHand;

class Player
{
    private $playerHand;
    private $playerNumber;

    public function __construct($playerNumber = null)
    {
        $this->playerHand = new CardHand();
        $this->playerNumber = $playerNumber;
    }

    public function giveCard(Card $newCard)
    {
        $this->playerHand->addCard($newCard);
    }

    public function getHand()
    {
        return $this->playerHand;
    }
    public function getPlayerName(): string
    {
        return "Player " . $this->playerNumber;
    }

    public function setNumber(int $num)
    {
        $this->playerNumber = $num;
    }
}
