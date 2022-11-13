<?php

namespace App\Card;

use App\Card\Card;

/**
 * Class representing a collection of playing cards in a hand.
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
class CardHand
{
    /**
     * Collection of cards in hand
     * @var array<Card> 
    */
    private $hand = [];

    /**
     * Total points of hands (used by 21 game)
     * @var int 
    */
    private $points;

    /**
     *
     * Adds a card to hand
     * @return void
    */
    public function addCard(Card $newCard)
    {
        $this->hand[] = $newCard;
    }

    /**
     *
     * Sorts cards in hand according to rank and suit and returns them
     *
     * @return array<Card>
    */
    public function getRankSortedCards()
    {
        $sortedCardArray = array_merge(array(), $this->hand);
        usort($sortedCardArray, function ($cardA, $cardB) {
            if ($cardA->getRank() == $cardB->getRank()) {
                return(strcmp($cardA->getSuit(), $cardB->getSuit()));
            }
            return($cardA->getRank() - $cardB->getRank());
        });
        return $sortedCardArray;
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

    /**
     *
     * Returns total points of hand (for game 21)
     *
     * @return array<Card>
    */
    public function getPoints(): int
    {
        $newTotal = 0;
        $numberOfAces = 0;

        // Add points from cards except aces (and count aces)
        foreach ($this->hand as $card) {
            $cardRank = $card->getRank();
            if ($cardRank == 1) {
                $numberOfAces++;
            } else {
                $newTotal = $newTotal + $card->getRank();
            }
        }

        //Add aces value
        if ($numberOfAces > 0) {
            $maxAceValue = 14 + ($numberOfAces - 1) * 1;
            if (($maxAceValue + $newTotal) <= 21) {
                $newTotal = $newTotal + $maxAceValue;
            } else {
                $newTotal = $newTotal + $numberOfAces;
            }
        }

        $this->points = $newTotal;
        return $this->points;
    }
}
