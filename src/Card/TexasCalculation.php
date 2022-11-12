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
        $comparisonResults = array ("first_player_points" => $resultHand1["points"],
        "second_player_points" => $resultHand2["points"],
        "first_player_combination" => $resultHand1["combination"],
        "second_player_combination" => $resultHand2["combination"], "won_by_rank" => false);


        /* Compares points to get winner */
        if ($resultHand1["points"] > $resultHand2["points"]) {
            $comparisonResults["winner"] = 1;
        } elseif ($resultHand1["points"] < $resultHand2["points"]) {
            $comparisonResults["winner"] = 2;
        } elseif ($resultHand1["points"] == $resultHand2["points"]) {
            //Compare ranks of cards in combinaton if both players have same points
            $comparisonResults["winner"] = 0;
            $comparisonResults["won_by_rank"] = true;
            for ($x = count($resultHand1["rank"]) - 1; $x > -1; $x--) {
                if ($resultHand1["rank"][$x] > $resultHand2["rank"][$x]) {
                    $comparisonResults["winner"] = 1;
                    break;
                } elseif ($resultHand1["rank"][$x] < $resultHand2["rank"][$x]) {
                    $comparisonResults["winner"] = 2;
                    break;
                }
            }

            //If winner still hasn't been found, compare ranks of extra cards.
            // Since both players have the same combination (e.g. two pairs)
            // they will have the same amount of remaining cards!
            if ($comparisonResults["winner"] == 0) {
                for ($x = count($resultHand1["remaining_card_ranks"]) - 1; $x > -1; $x--) {
                    if ($resultHand1["remaining_card_ranks"][$x] > $resultHand2["remaining_card_ranks"][$x]) {
                        $comparisonResults["winner"] = 1;
                        break;
                    } elseif ($resultHand1["remaining_card_ranks"][$x] < $resultHand2["remaining_card_ranks"][$x]) {
                        $comparisonResults["winner"] = 2;
                        break;
                    }
                }
            }
        }

        return $comparisonResults;
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

        $result = $pokerHandChecksOne->checkStraightFlush($sortedCards);
        if ($result["found"]) {
            $result["points"] = 8;
            $result["combination"] = "färgstege";
            return $result;
        }

        $result = $pokerHandChecksOne->checkFourOfAKind($sortedCards);
        if ($result["found"]) {
            $result["points"] = 7;
            $result["combination"] = "fyrtal";
            return $result;
        }

        $result = $pokerHandChecksOne->checkFullHouse($sortedCards);
        if ($result["found"]) {
            $result["points"] = 6;
            $result["combination"] = "kåk";
            return $result;
        }

        $result = $pokerHandChecksOne->checkFlush($sortedCards);
        if ($result["found"]) {
            $result["points"] = 5;
            $result["combination"] = "färg";
            return $result;
        }


        $result = $pokerHandChecksTwo->checkStraight($sortedCards);
        if ($result["found"]) {
            $result["points"] = 4;
            $result["combination"] = "stege";
            return $result;
        }

        $result = $pokerHandChecksTwo->checkThreeOfAKind($sortedCards);
        if ($result["found"]) {
            $result["points"] = 3;
            $result["combination"] = "triss";
            return $result;
        }

        $result = $pokerHandChecksTwo->checkTwoPairs($sortedCards);
        if ($result["found"]) {
            $result["points"] = 2;
            $result["combination"] = "två par";
            return $result;
        }

        $result = $pokerHandChecksTwo->checkPair($sortedCards);
        if ($result["found"]) {
            $result["points"] = 1;
            $result["combination"] = "par";
            return $result;
        }

        $highestCards = array_slice($sortedCards, -5);
        $result = array("points" => 0, "remaining_card_ranks" => $highestCards,
        "found" => false, "rank" => [], "combination" => "inget");
        return $result;
    }
}
