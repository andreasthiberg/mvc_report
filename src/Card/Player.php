<?php

namespace App\Card;

use App\Card\CardHand;

class Player
{
    /**
     * @var CardHand Player's hand 
    */ 
    private $playerHand;
    private int $playerNumber;

    public function __construct(int $playerNumber)
    {
        $this->playerHand = new CardHand();
        $this->playerNumber = $playerNumber;
    }

    public function giveCard(Card $newCard): void
    {
        $this->playerHand->addCard($newCard);
    }

    public function getHand(): CardHand
    {
        return $this->playerHand;
    }
    public function getPlayerName(): string
    {
        return "Player " . $this->playerNumber;
    }

    public function setNumber(int $num): void
    {
        $this->playerNumber = $num;
    }
}
