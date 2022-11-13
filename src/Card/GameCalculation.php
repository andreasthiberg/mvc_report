<?php

namespace App\Card;

use App\Card\CardHand;
use App\Card\Card;

/**
 * Class with helper functions to make calculations for 21 game
*/
class GameCalculation
{
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
            $winner = ($userPoints <= $bankPoints ? "bank" : "user");
        }
        return $winner;
    }
}
