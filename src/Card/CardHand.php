<?php

namespace App\Card;

use App\Card\Card;

class CardHand
{
    /**
     * @var array<Card> collection of cards in hand
    */
    private $hand = [];

    private int $points;

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
    public function getCards()
    {
        return $this->hand;
    }

    public function getPoints(): int
    {
        $newTotal = 0;
        $numberOfAces = 0;

        // Add points from cards except aces (and count aces)
        foreach ($this->hand as $card) {
            $cardRank = $card->getRank();
            if($cardRank == 1){
                $numberOfAces++;
            } else {
                $newTotal = $newTotal + $card->getRank();
            }
        }

        //Add aces value
        if($numberOfAces > 0){
            $maxAceValue = 14 + ($numberOfAces-1)*1;
            $minAceValue = $numberOfAces;
            if(($maxAceValue + $newTotal) <= 21 ){
                $newTotal = $newTotal + $maxAceValue;
            } else {
                $newTotal = $newTotal + $numberOfAces;
            }
        }

        $this->points = $newTotal;
        return $this->points;
    }
}
