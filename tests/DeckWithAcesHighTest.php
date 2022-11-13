<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckWithAcesHigh.
 */
class DeckWithAcesHighTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateObject()
    {
        $deck = new DeckWithAcesHigh();
        $this->assertInstanceOf("\App\Card\DeckWithAcesHigh", $deck);

        $cards = $deck->getCards();
        $this->assertEquals(14, $cards[51]->getRank());
    }
}
