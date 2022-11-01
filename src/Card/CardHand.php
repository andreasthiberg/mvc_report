<?php

namespace App\Card;

use App\Card\Card;

class CardHand
{
    /**
     * @var array<Card> collection of cards in hand
    */
    private $hand;

    /**
     *
     * Adds a card to hand
     *
     * @return void
    */
    public function addCard(Card $newCard)
    {
        $this->hand[] = $newCard;
    }

    /**
     *
     * Returns cards in hand
     *
     * @return array<Card>
    */
    public function getCards(): array
    {
        return $this->hand;
    }
}
