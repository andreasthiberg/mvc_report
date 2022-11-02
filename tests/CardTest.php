<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateObjectWithArguments()
    {
        $card = new Card("Hearts",1);
        $this->assertInstanceOf("\App\Card\Card", $card);

        $suitRes = $card->getSuit();
        $suitExp = "Hearts";
        $rankRes = $card->getRank();
        $rankExp = 1;

        $this->assertEquals($suitExp, $suitRes);
        $this->assertEquals($rankExp, $rankRes);
    }

    /**
     * Verifies that the correct color (red/black) is given
     * based on suit.
     */
    public function testGivenColor() {
        $cardHearts = new Card("Hearts",1);
        $cardDiamonds = new Card("Diamonds",1);
        $cardClubs = new Card("Clubs",1);
        $cardSpades = new Card("Spades",1);

        $this->assertEquals("red", $cardHearts->getColor());
        $this->assertEquals("red", $cardDiamonds->getColor());
        $this->assertEquals("black", $cardSpades->getColor());
        $this->assertEquals("black", $cardClubs->getColor());
    }

    /**
     * Verifies that the correct string version is given of 
     * rank (e.g. K for 13)
     */
    public function testStringRAnk() {
        $cardKing = new Card("Hearts",13);
        $cardJoker = new Card("Joker",0);
        $cardQueen = new Card("Clubs",12);
        $cardJack = new Card("Hearts",11);
        $cardAce = new Card("Hearts",1);
        $cardTwo = new Card("Hearts",2);

        $this->assertEquals("K", $cardKing->getRankStringIncludingJoker());
        $this->assertEquals("Jo", $cardJoker->getRankStringIncludingJoker());
        $this->assertEquals("J", $cardJack->getRankStringIncludingJoker());
        $this->assertEquals("Q", $cardQueen->getRankStringIncludingJoker());
        $this->assertEquals("A", $cardAce->getRankStringIncludingJoker());
        $this->assertEquals("2", $cardTwo->getRankStringIncludingJoker());
    }

    /**
     * Verifies that the correct symbol is recieved based on suit.
     */
    public function testGetSymbol() {
        $cardHearts = new Card("Hearts",13);
        $cardClubs = new Card("Clubs",12);
        $cardSpades = new Card("Spades",11);
        $cardDiamonds = new Card("Diamonds",1);
        $cardJoker = new Card("Joker",0);

        $this->assertEquals("♥", $cardHearts->getSymbol());
        $this->assertEquals("♣", $cardClubs->getSymbol());
        $this->assertEquals("♠", $cardSpades->getSymbol());
        $this->assertEquals("♦", $cardDiamonds->getSymbol());
        $this->assertEquals("?", $cardJoker->getSymbol());


    }


}
