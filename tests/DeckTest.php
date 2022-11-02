<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Deck.
 */
class DeckTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateObject()
    {
        $deck = new Deck();
        $this->assertInstanceOf("\App\Card\Deck", $deck);

        $this->assertInstanceOf("\App\Card\Card", $deck->getCards()[0]);
    }

    /**
     * Creates two decks, shuffles one and then makes sure they're not the same.
     */
    public function testShuffleDeck()
    {
        $deck1 = new Deck();
        $deck2 = new Deck();
        $this->assertEquals($deck2, $deck1);
        $deck2->shuffleDeck();
        $this->assertNotEquals($deck2, $deck1);
    }

    /**
     * Tests if draw removes cards and returns them.
     */
    public function testDrawCard()
    {
        $deck = new Deck();
        $cards = $deck->drawCards(1); 
        $this->assertInstanceOf("\App\Card\Card", $cards[0]);
        $this->assertNotContains($cards[0], $deck->getCards());
    }

    /**
     * Tests sorting a shuffled deck
     */
    public function testSortingShuffledDeck()
    {
        $notShuffledDeck = new Deck();
        $shuffleDeck = new Deck();
        $shuffleDeck->shuffleDeck();

        $this->assertNotEquals($notShuffledDeck, $shuffleDeck);
        $this->assertEquals($shuffleDeck->getSortedCards(), $notShuffledDeck->getCards());



    }
}
