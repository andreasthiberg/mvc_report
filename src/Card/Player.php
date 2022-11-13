<?php

namespace App\Card;

use App\Card\CardHand;

/**
 * Class representing a player, who can hold a hand of cards
 *
*/
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

    /**
     * Gives the player a card
    */
    public function giveCard(Card $newCard): void
    {
        $this->playerHand->addCard($newCard);
    }

    /**
     * Returns the player's hand of cards
    */
    public function getHand(): CardHand
    {
        return $this->playerHand;
    }

    /**
     * Gets the name of the player (Player + playerNumber)
    */
    public function getPlayerName(): string
    {
        return "Player " . $this->playerNumber;
    }

    /**
     * Sets the player's id-number
    */
    public function setNumber(int $num): void
    {
        $this->playerNumber = $num;
    }
}
