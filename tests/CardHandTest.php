<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardHand.
 */
class CardHandTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateObject()
    {
        $cardHand = new CardHand();
        $this->assertInstanceOf("\App\Card\CardHand", $cardHand);

        $this->assertEquals(0, $cardHand->getPoints());
        $this->assertEquals([], $cardHand->getCards());
    }

    /**
     * Add cards and verify they're in hand.
     */
    public function testHandWithCards()
    {
        $cardHand = new CardHand();
        $testCard1 = new Card("Hearts", 2);
        $testCard2 = new Card("Clubs", 11);
        $cardHand->addCard($testCard1);
        $cardHand->addCard($testCard2);

        $this->assertEquals([$testCard1, $testCard2], $cardHand->getCards());
    }

    /**
     * Add cards and verify that points are calculated correctly.
     */
    public function testCountingPoints()
    {
        $cardHand = new CardHand();
        $testCard1 = new Card("Hearts", 1);
        $testCard2 = new Card("Clubs", 6);
        $cardHand->addCard($testCard1);
        $cardHand->addCard($testCard2);

        $this->assertEquals(20, $cardHand->getPoints());
    }


    /**
     * Add cards and verify that points are calculated correctly with multiple aces.
     */
    public function testCountingPointsWithAces()
    {
        $cardHand = new CardHand();
        $testCard1 = new Card("Hearts", 1);
        $testCard2 = new Card("Diamonds", 1);
        $testCard3 = new Card("Clubs", 10);
        $cardHand->addCard($testCard1);
        $cardHand->addCard($testCard2);
        $cardHand->addCard($testCard3);

        $this->assertEquals(12, $cardHand->getPoints());
    }
}
