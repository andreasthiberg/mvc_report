<?php

namespace App\Card;

class GameCalculation {
    /**
     *
     * Calculates winner based on input
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
}