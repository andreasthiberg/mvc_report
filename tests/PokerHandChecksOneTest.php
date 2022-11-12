<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class PokerHandChecksOne.
 */
class PokerHandChecksOneTest extends TestCase
{

     /**
     * Test method that looks for a straight flush.
     */
    public function testFindingStraightFlush()
    {
        $calc = new PokerHandChecksOne();

        // Check hand with 5 card straight flush
        $cardRanks = [1,4,5,6,7,8,12];
        $cardSuits = ["Hearts","Spades","Spades","Spades","Spades","Spades","Clubs"];
        
        $cards = [];
        for ($x = 0; $x < 7; $x++){
            $cards[] = new Card($cardSuits[$x],$cardRanks[$x]);
        }
        $result = $calc->checkStraightFlush($cards);
        $this->assertEquals(True, $result["found"]);
        $this->assertEquals([8], $result["rank"]);

        // Check hand with straight without flush
        $cardRanks = [1,2,3,4,5,5,6];
        $cardSuits = ["Spades","Spades","Diamonds","Hearts","Clubs","Hearts","Spades"];
        
        $cards = [];
        for ($x = 0; $x < 7; $x++){
            $cards[] = new Card($cardSuits[$x],$cardRanks[$x]);
        }
        $result = $calc->checkStraightFlush($cards);
        $this->assertEquals(False, $result["found"]);

        // Check hand without straight
        $cardRanks = [1,2,4,5,6,7,12];
        $cardSuits = ["Spades","Spades","Diamonds","Hearts","Clubs","Hearts","Spades"];
        
        $cards = [];
        for ($x = 0; $x < 7; $x++){
            $cards[] = new Card($cardSuits[$x],$cardRanks[$x]);
        }
        $result = $calc->checkStraightFlush($cards);
        $this->assertEquals(False, $result["found"]);

    }

    /**
     * Test method that looks for 4 matching cards
     */
    public function testFindingFourOfAKind()
    {
        $calc = new PokerHandChecksOne();

        // Check hand with four of a kind
        $cardRanks = [1,2,4,4,4,4,12];
        $cardSuits = ["Spades","Spades","Diamonds","Hearts","Clubs","Hearts","Spades"];

        $cards = [];
        for ($x = 0; $x < 7; $x++){
            $cards[] = new Card($cardSuits[$x],$cardRanks[$x]);
        }

        $result = $calc->checkFourOfAKind($cards);
        $this->assertEquals(True, $result["found"]);
        $this->assertEquals([4], $result["rank"]);
        $this->assertEquals([12], $result["remaining_card_ranks"]);

        // Check hand without four of a kind
        $cardRanks = [1,2,3,4,4,4,12];
        $cardSuits = ["Spades","Spades","Diamonds","Hearts","Clubs","Hearts","Spades"];

        $cards = [];
        for ($x = 0; $x < 7; $x++){
            $cards[] = new Card($cardSuits[$x],$cardRanks[$x]);
        }

        $result = $calc->checkFourOfAKind($cards);
        $this->assertEquals(False, $result["found"]);

    }

    /**
     * Test method that looks for a full house
     */
    public function testFindingFullHouse()
    {
        $calc = new PokerHandChecksOne();

        // Check hand with full house
        $cardRanks = [5,6,6,6,9,13,13];
        $cardSuits = ["Spades","Spades","Diamonds","Hearts","Clubs","Hearts","Spades"];

        $cards = [];
        for ($x = 0; $x < 7; $x++){
            $cards[] = new Card($cardSuits[$x],$cardRanks[$x]);
        }

        $result = $calc->checkFullHouse($cards);
        $this->assertEquals(True, $result["found"]);
        $this->assertEquals([6,13], $result["rank"]);
        $this->assertEquals([], $result["remaining_card_ranks"]);

        // Check hand without four of a kind
        $cardRanks = [7,7,8,8,10,10,12];
        $cardSuits = ["Spades","Spades","Diamonds","Hearts","Clubs","Hearts","Spades"];

        $cards = [];
        for ($x = 0; $x < 7; $x++){
            $cards[] = new Card($cardSuits[$x],$cardRanks[$x]);
        }

        $result = $calc->checkFullHouse($cards);
        $this->assertEquals(False, $result["found"]);

    }

    /**
     * Test method that looks for three of a kind
     */
    public function testFindingFlush()
    {
        $calc = new PokerHandChecksOne();

        // Check hand with flush
        $cardRanks = [1,5,6,6,6,12,13];
        $cardSuits = ["Spades","Hearts","Spades","Hearts","Spades","Spades","Spades"];

        $cards = [];
        for ($x = 0; $x < 7; $x++){
            $cards[] = new Card($cardSuits[$x],$cardRanks[$x]);
        }

        $result = $calc->checkFlush($cards);
        $this->assertEquals(True, $result["found"]);
        $this->assertEquals([1,6,6,12,13], $result["rank"]);
        $this->assertEquals([], $result["remaining_card_ranks"]);

        // Check hand without flush
        $cardRanks = [7,7,8,8,10,10,12];
        $cardSuits = ["Spades","Spades","Diamonds","Hearts","Clubs","Hearts","Spades"];

        $cards = [];
        for ($x = 0; $x < 7; $x++){
            $cards[] = new Card($cardSuits[$x],$cardRanks[$x]);
        }

        $result = $calc->checkFlush($cards);
        $this->assertEquals(False, $result["found"]);

    }

}
