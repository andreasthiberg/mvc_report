<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckWith2Jokers.
 */
class DeckWith2JokersTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateObject()
    {
        $deck = new DeckWith2Jokers();
        $this->assertInstanceOf("\App\Card\DeckWith2Jokers", $deck);

        $cards = $deck->getCards();    
        $this->assertEquals("Joker",$cards[53]->getSuit());
    }

}
