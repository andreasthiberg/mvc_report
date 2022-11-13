<?php

namespace App\Card;

use App\Card\CardHand;
use App\Card\Card;

/**
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 */

class TexasCalculation
{
    /**
     *
     * Compares two texas hold em hands to get the winner
     *
     * @return mixed[]
    */
    public function compareTexasHands(
        CardHand $firstHoleCardsHand,
        CardHand $secondHoleCardsHand,
        CardHand $tableCardHand
    ) {

        /* Build complete 7 card hands for both players */
        $fullHand1 = new CardHand();
        $fullHand2 = new CardHand();
        $firstHoleCards = $firstHoleCardsHand->getCards();
        $secondHoleCards = $secondHoleCardsHand->getCards();
        $tableCards = $tableCardHand->getCards();

        for ($x = 0; $x < 2; $x++) {
            $fullHand1->addCard($firstHoleCards[$x]);
            $fullHand2->addCard($secondHoleCards[$x]);
        }

        for ($x = 0; $x < 5; $x++) {
            $fullHand1->addCard($tableCards[$x]);
            $fullHand2->addCard($tableCards[$x]);
        }

        /* Get best combination and extra cards for both hands */
        $resultHand1 = $this->getPointsForFullSetOfCards($fullHand1);
        $resultHand2 = $this->getPointsForFullSetOfCards($fullHand2);


        return $this->compareHandResults($resultHand1,$resultHand2);
    }

    /**
     *
     * Compare results of two analysed 7 card hands 
     *
     * @param mixed[] $resultHand1
     * @param mixed[] $resultHand2
     * @return mixed[]
    */
    public function compareHandResults($resultHand1, $resultHand2){

        $comparisonResults = array ("first_player_points" => $resultHand1["points"],
        "second_player_points" => $resultHand2["points"],
        "first_player_combination" => $resultHand1["combination"],
        "second_player_combination" => $resultHand2["combination"], "won_by_rank" => false);


        /* Compares points to get winner. */
        $comparisonResults["winner"] = $resultHand1["points"] < $resultHand2["points"] ? 2 : 1;
        
        if ($resultHand1["points"] == $resultHand2["points"]) {
            //Compare ranks of cards in combinaton if both players have same points
            $comparisonResults["winner"] = 0;
            $comparisonResults["won_by_rank"] = true;
            $comparisonResults["winner"] = $this->compareRanks($resultHand1["rank"], $resultHand2["rank"]);
            
            // If winner still hasn't been found, compare ranks of extra cards.
            // Since both players have the same combination (e.g. two pairs)
            // they will have the same amount of remaining cards!
            if ($comparisonResults["winner"] == 0) {
                $comparisonResults["winner"] = $this->compareRanks($resultHand1["remaining_card_ranks"], $resultHand2["remaining_card_ranks"]);;
            }
        }
        return $comparisonResults;
    }


    /**
     *
     * Compare ranks of cards to decide between two hands - returns 1 or 2 or 0 based on winner (0 = no winner).
     * 
     * @param array<int> $firstRanks
     * @param array<int> $secondRanks
     * @return int;
    */
    public function compareRanks($firstRanks, $secondRanks){
        for ($x = count($firstRanks) - 1; $x > -1; $x--) {
            if ($firstRanks[$x] > $secondRanks[$x]) {
                return 1;
            } elseif ($firstRanks[$x] < $secondRanks[$x]) {
                return 2;
            }
        }
        return 0;
    }


    /**
     *
     * Gets best poker hands (in points) for a 5-card hand out of 7 cards
     * Checks for each possible combination in order of rank
     *
     * @param CardHand $cardHand
     * @return mixed[]
    */
    public function getPointsForFullSetOfCards($cardHand)
    {
        $pokerHandChecksOne = new PokerHandChecksOne();
        $pokerHandChecksTwo = new PokerHandChecksTwo();
        $sortedCards = $cardHand->getRankSortedCards();

        //Check each possible point giving hand in order of points given. If no combination is found,
        // return 0 points and five highest cards.

        // Array of all hand checking functions
        $functionArray = array (
            8 => [ $pokerHandChecksOne, "checkStraightFlush"],
            7 => [ $pokerHandChecksOne, "checkFourOfAKind"],
            6 => [ $pokerHandChecksOne, "checkFullHouse"],
            5 => [ $pokerHandChecksOne, "checkFlush"],
            4 => [ $pokerHandChecksTwo, "checkStraight"],
            3 => [ $pokerHandChecksTwo, "checkThreeOfAKind"],
            2 => [ $pokerHandChecksTwo, "checkTwoPairs"],
            1 => [ $pokerHandChecksTwo, "checkPair"]
        );

        // Array of combination names
        $combinationArray = array (
            8 => "färgstege",
            7 => "fyrtal",
            6 => "kåk",
            5 => "färg",
            4 => "stege",
            3 => "triss",
            2 => "två par",
            1 => "par",
        );
        
        // Run all combinat functions until match is found - starting at best combination
        for($x = 8; $x > 0; $x--){
            $result = call_user_func_array([$functionArray[$x][0], $functionArray[$x][1]], [$sortedCards]);
            if($result["found"]){
                $result["points"] = $x;
                $result["combination"] = $combinationArray[$x];
                return $result;
            }
        }

        //Get all cards if no combination found
        $highestCards = array_slice($sortedCards, -5);
        $result = array("points" => 0, "remaining_card_ranks" => $highestCards,
        "found" => false, "rank" => [], "combination" => "inget");
        return $result;
    }
}
