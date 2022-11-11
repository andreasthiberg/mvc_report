<?php

namespace App\Card;

use App\Card\CardHand;

class GameCalculation {

    /**
     *
     * Calculates winner based on bank and player points in 21
     *
     * @return string
    */
    public function calculateWinner(int $bankPoints, int $userPoints)
    {
        $winner = "";
        if ($bankPoints == 21) {
            $winner = "bank";
        } elseif ($bankPoints > 21) {
            $winner = "user";
        } elseif ($bankPoints >= 17) {
            if ($userPoints <= $bankPoints) {
                $winner = "bank";
            } else {
                $winner = "user";
            }
        }
        return $winner;
    }

    /**
     *
     * Calculates maximum points for a texas hold'em hand
     *
     * @return int
    */
    public function calculateTexasPoints(CardHand $hand)
    {
        $points = 0;
        return $points;
    }

}