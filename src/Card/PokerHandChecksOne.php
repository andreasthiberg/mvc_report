<?php

namespace App\Card;

use App\Card\CardHand;
use App\Card\TexasCalculation;
use App\Card\Card;

/**
 * Class with methods to look for card combination in poker hand (first of two)
 * @SuppressWarnings(PHPMD.ElseExpression)
 */

class PokerHandChecksOne
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
     * Gets only the suits of a card hand as an array
     * @param array<Card> $sortedCards
     * @return array<string>
     */
    public function getSuits($sortedCards)
    {
        $suitArray = [];
        for ($y = 0; $y < 7; $y++) {
            $suitArray[] = $sortedCards[$y]->getSuit();
        }
        return $suitArray;
    }

    /**
     * Checks cards for straight flush
     * @param array<Card> $sortedCards
     * @return mixed[]
     */
    public function checkStraightFlush($sortedCards)
    {

        $suitArray = $this->getSuits($sortedCards);
        $rankArray = $this->getRanks($sortedCards);

        /* Variables for tracking straight */
        $straightFlushFound = false;
        $straightCount = 1;
        $highestRank = 0;

        /* Check for 5 card straight */
        for ($y = 0; $y < 6; $y++) {
            if ($rankArray[$y + 1] - $rankArray[$y] == 0) {
            } elseif ($rankArray[$y + 1] - $rankArray[$y] == 1 && $suitArray[$y] == $suitArray[$y + 1]) {
                $straightCount++;
            } else {
                $straightCount = 1;
            }
            if ($straightCount >= 5) {
                $straightFlushFound = true;
                if ($rankArray[$y + 1] > $highestRank) {
                    $highestRank = $rankArray[$y + 1];
                }
            }
        }

        return array ( "found" => $straightFlushFound, "rank" => [$highestRank]);
    }

    /**
     * Checks cards for four of a kind
     * @param array<Card> $sortedCards
     * @return mixed[]
     */
    public function checkFourOfAKind($sortedCards)
    {

        $rankArray = $this->getRanks($sortedCards);
        $result = array ("found" => false, "rank" => [], "remaining_card_ranks" => []);
        $countedRanksArray = array_count_values($rankArray);

        foreach ($countedRanksArray as $key => $value) {
            if ($value === 4) {
                $result["found"] = true;
                $result["rank"] = [$key];
            }
        }

        //Get hightest fifth card
        if ($result["found"]) {
            $currentMax = 0;
            for ($x = 0; $x < 7; $x++) {
                if ($rankArray[$x] != $result["rank"][0] && $rankArray[$x] > $currentMax) {
                    $currentMax = $rankArray[$x];
                }
            }
            $result["remaining_card_ranks"] = [$currentMax];
        }

        return $result;
    }

    /**
     * Checks cards for full house
     * @param array<Card> $sortedCards
     * @return mixed[]
     */
    public function checkFullHouse($sortedCards)
    {

        $rankArray = $this->getRanks($sortedCards);
        $result = array ("found" => false, "rank" => [], "remaining_card_ranks" => []);
        $countedRanksArray = array_count_values($rankArray);

        $threeFound = false;
        $maxThreeRank = 0;

        //Finds three of a kind of highest rank
        foreach ($countedRanksArray as $key => $value) {
            if ($value >= 3) {
                $threeFound = true;
                if ($key > $maxThreeRank) {
                    $maxThreeRank = $key;
                    $result["rank"][0] = $maxThreeRank;
                }
            }
        }

        //Looks for the best pair excluding the three of a kind
        if ($threeFound) {
            $maxPairRank = 0;
            foreach ($countedRanksArray as $key => $value) {
                if ($value >= 2 && $key !== $maxThreeRank) {
                    $result["found"] = true;
                    if ($key > $maxPairRank) {
                        $maxPairRank = $key;
                        $result["rank"][1] = $maxPairRank;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Checks cards for flush
     * @param array<Card> $sortedCards
     * @return mixed[]
     */
    public function checkFlush($sortedCards)
    {

        $result = array ("found" => false, "rank" => [], "remaining_card_ranks" => []);

        /* Get all suits and ranks */
        $suitArray = $this->getSuits($sortedCards);
        $rankArray = $this->getRanks($sortedCards);

        /* Variables to keep track of flush */
        $flushFound = false;
        $flushSuit = "";
        $flushRanks = [];

        /* Count suit occurences */
        $countedSuitsArray = array_count_values($suitArray);

        foreach ($countedSuitsArray as $key => $value) {
            if ($value >= 5) {
                $flushFound = true;
                $flushSuit = $key;
                $result["found"] = true;
            }
        }

        /* Find five highest ranked cards of flush suit */
        if ($flushFound) {
            $flushRanks = [];
            for ($x = 0; $x < 7; $x++) {
                if ($suitArray[$x] == $flushSuit) {
                    $flushRanks[] = $rankArray[$x];
                }
            }
        }
        $result["rank"] = array_slice($flushRanks, -5);
        return $result;
    }
}
