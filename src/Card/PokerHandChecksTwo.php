<?php

namespace App\Card;

use App\Card\CardHand;
use App\Card\TexasCalculation;
use App\Card\Card;

/**
 * Class with methods to look for card combination in poker hand (second of two)
 * @SuppressWarnings(PHPMD.ElseExpression)
 */

class PokerHandChecksTwo
{
    /**
     * Gets only the ranks of a card hand as an array
     * @param array<Card> $sortedCards
     * @return array<int>
     */
    public function getRanks($sortedCards)
    {
        $rankArray = [];
        for ($y = 0; $y < 7; $y++) {
            $rankArray[] = $sortedCards[$y]->getRank();
        }
        return $rankArray;
    }

    /**
     * Checks cards for straight
     * @param array<Card> $sortedCards
     * @return mixed[]
     */
    public function checkStraight($sortedCards): array
    {

        $rankArray = $this->getRanks($sortedCards);

        /* Variables for tracking straight */
        $straightFound = false;
        $straightCount = 1;
        $highestRank = 0;

        /* Check for 5 card straight */
        for ($y = 0; $y < 6; $y++) {
            if ($rankArray[$y + 1] - $rankArray[$y] == 0) {
            } elseif ($rankArray[$y + 1] - $rankArray[$y] == 1) {
                $straightCount++;
            } else {
                $straightCount = 1;
            }
            if ($straightCount >= 5) {
                $straightFound = true;
                if ($rankArray[$y + 1] > $highestRank) {
                    $highestRank = $rankArray[$y + 1];
                }
            }
        }

        return array ( "found" => $straightFound, "rank" => [$highestRank]);
    }


    /**
     * Checks cards for three of a kind
     * @param array<Card> $sortedCards
     * @return mixed[]
     */
    public function checkThreeOfAKind($sortedCards): array
    {

        $rankArray = $this->getRanks($sortedCards);
        $result = array ("found" => false, "rank" => [], "remaining_card_ranks" => []);
        $countedRanksArray = array_count_values($rankArray);

        $maxThreeRank = 0;

        //Finds three of a kind of highest rank
        foreach ($countedRanksArray as $key => $value) {
            if ($value >= 3) {
                $result["found"] = true;
                if ($key > $maxThreeRank) {
                    $maxThreeRank = $key;
                    $result["rank"] = [$maxThreeRank];
                }
            }
        }

        //Get remaining cards
        $remainingRankArray = [];
        foreach ($rankArray as $cardRank) {
            if ($cardRank != $maxThreeRank) {
                $remainingRankArray[] = $cardRank;
            }
        }

        //Get two highest remaining cards
        $result["remaining_card_ranks"] = array_slice($remainingRankArray, -2);

        return $result;
    }

    /**
     * Checks cards for two pairs
     * @param array<Card> $sortedCards
     * @return mixed[]
     */
    public function checkTwoPairs($sortedCards): array
    {

        $rankArray = $this->getRanks($sortedCards);
        $result = array ("found" => false, "rank" => [], "remaining_card_ranks" => []);
        $countedRanksArray = array_count_values($rankArray);

        $pairRanks = [];

        //Finds all pairs
        foreach ($countedRanksArray as $key => $value) {
            if ($value >= 2) {
                $pairRanks[] = $key;
            }
        }

        //Get two highest pairs if they exist
        if (count($pairRanks) > 1) {
            sort($pairRanks);
            $pairRanks = array_slice($pairRanks, -2);
            $result["rank"] = $pairRanks;
            $result["found"] = true;
        }

        //Get highest remaining card
        $highestRemainingCard = 0;
        foreach ($rankArray as $cardRank) {
            if ($cardRank >= $highestRemainingCard && !in_array($cardRank, $pairRanks)) {
                $highestRemainingCard = $cardRank;
            }
        }
        $result["remaining_card_ranks"] = [$highestRemainingCard];

        return $result;
    }

    /**
     * Checks cards for pair
     * @param array<Card> $sortedCards
     * @return mixed[]
     */
    public function checkPair($sortedCards): array
    {

        $rankArray = $this->getRanks($sortedCards);
        $result = array ("found" => false, "rank" => [], "remaining_card_ranks" => []);
        $countedRanksArray = array_count_values($rankArray);

        $pairRanks = [];

        //Finds all pairs
        foreach ($countedRanksArray as $key => $value) {
            if ($value >= 2) {
                $pairRanks[] = $key;
            }
        }

        //Get highest ranked pair if they exist
        if (count($pairRanks) >= 1) {
            sort($pairRanks);
            $pairRanks = array_slice($pairRanks, -1);
            $result["rank"] = $pairRanks;
            $result["found"] = true;
        }

        $remainingRankArray = [];
        //Get remaining cards
        if ($result["found"]) {
            foreach ($rankArray as $cardRank) {
                if ($cardRank != $pairRanks[0]) {
                    $remainingRankArray[] = $cardRank;
                }
            }
        }

        //Get three highest remaining cards
        $result["remaining_card_ranks"] = array_slice($remainingRankArray, -3);

        return $result;
    }
}
