<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class PokerHandChecksTwo.
 */
class PokerHandChecksTwoTest extends TestCase
{
    /**
     * Test method that looks for a straight.
     */
    public function testFindingStraight()
    {
        $calc = new PokerHandChecksTwo();

        // Check hand with 5 card straight (should return 8 as highest card)
        $cardRanks = [1,4,5,6,7,8,12];
        $cardSuits = ["Spades","Spades","Diamonds","Hearts","Clubs","Hearts","Spades"];
        
        $cards = [];
        for ($x = 0; $x < 7; $x++){
            $cards[] = new Card($cardSuits[$x],$cardRanks[$x]);
        }
        $result = $calc->checkStraight($cards);
        $this->assertEquals(True, $result["found"]);
        $this->assertEquals([8], $result["rank"]);

        // Check hand with 7 card straight (should return 6 as highest card)
        $cardRanks = [1,2,3,4,5,5,6];
        $cardSuits = ["Spades","Spades","Diamonds","Hearts","Clubs","Hearts","Spades"];
        
        $cards = [];
        for ($x = 0; $x < 7; $x++){
            $cards[] = new Card($cardSuits[$x],$cardRanks[$x]);
        }
        $result = $calc->checkStraight($cards);
        $this->assertEquals(True, $result["found"]);
        $this->assertEquals([6], $result["rank"]);

        // Check hand without straight
        $cardRanks = [1,2,4,5,6,7,12];
        $cardSuits = ["Spades","Spades","Diamonds","Hearts","Clubs","Hearts","Spades"];
        
        $cards = [];
        for ($x = 0; $x < 7; $x++){
            $cards[] = new Card($cardSuits[$x],$cardRanks[$x]);
        }
        $result = $calc->checkStraight($cards);
        $this->assertEquals(False, $result["found"]);

    }

    /**
     * Test method that looks for three of a kind
     */
    public function testFindingThreeOfAKind()
    {
        $calc = new PokerHandChecksTwo();

        // Check hand with three of a kind
        $cardRanks = [1,5,6,6,6,12,13];
        $cardSuits = ["Spades","Spades","Diamonds","Hearts","Clubs","Hearts","Spades"];

        $cards = [];
        for ($x = 0; $x < 7; $x++){
            $cards[] = new Card($cardSuits[$x],$cardRanks[$x]);
        }

        $result = $calc->checkThreeOfAKind($cards);
        $this->assertEquals(True, $result["found"]);
        $this->assertEquals([6], $result["rank"]);
        $this->assertEquals([12,13], $result["remaining_card_ranks"]);

        // Check hand without four of a kind
        $cardRanks = [7,7,8,8,10,10,12];
        $cardSuits = ["Spades","Spades","Diamonds","Hearts","Clubs","Hearts","Spades"];

        $cards = [];
        for ($x = 0; $x < 7; $x++){
            $cards[] = new Card($cardSuits[$x],$cardRanks[$x]);
        }

        $result = $calc->checkThreeOfAKind($cards);
        $this->assertEquals(False, $result["found"]);

    }

    /**
     * Test method that looks for two pairs
     */
    public function testFindingTwoPairs()
    {
        $calc = new PokerHandChecksTwo();

        // Check hand with two pairs
        $cardRanks = [1,5,6,6,8,13,13];
        $cardSuits = ["Spades","Spades","Diamonds","Hearts","Clubs","Hearts","Spades"];

        $cards = [];
        for ($x = 0; $x < 7; $x++){
            $cards[] = new Card($cardSuits[$x],$cardRanks[$x]);
        }

        $result = $calc->checkTwoPairs($cards);
        $this->assertEquals(True, $result["found"]);
        $this->assertEquals([6,13], $result["rank"]);
        $this->assertEquals([8], $result["remaining_card_ranks"]);

        // Check hand without two pairs
        $cardRanks = [3,4,8,9,10,11,12];
        $cardSuits = ["Spades","Spades","Diamonds","Hearts","Clubs","Hearts","Spades"];

        $cards = [];
        for ($x = 0; $x < 7; $x++){
            $cards[] = new Card($cardSuits[$x],$cardRanks[$x]);
        }

        $result = $calc->checkTwoPairs($cards);
        $this->assertEquals(False, $result["found"]);

    }

    /**
     * Test method that looks for a pair
     */
    public function testFindingPair()
    {
        $calc = new PokerHandChecksTwo();

        // Check hand with two pairs
        $cardRanks = [1,5,6,10,10,12,13];
        $cardSuits = ["Spades","Spades","Diamonds","Hearts","Clubs","Hearts","Spades"];

        $cards = [];
        for ($x = 0; $x < 7; $x++){
            $cards[] = new Card($cardSuits[$x],$cardRanks[$x]);
        }

        $result = $calc->checkPair($cards);
        $this->assertEquals(True, $result["found"]);
        $this->assertEquals([10], $result["rank"]);
        $this->assertEquals([6,12,13], $result["remaining_card_ranks"]);

        // Check hand without two pairs
        $cardRanks = [3,4,8,9,10,11,12];
        $cardSuits = ["Spades","Spades","Diamonds","Hearts","Clubs","Hearts","Spades"];

        $cards = [];
        for ($x = 0; $x < 7; $x++){
            $cards[] = new Card($cardSuits[$x],$cardRanks[$x]);
        }

        $result = $calc->checkPair($cards);
        $this->assertEquals(False, $result["found"]);

    }

}
