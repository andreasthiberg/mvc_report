<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Player.
 */
class PlayerTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateObjectWithArguments()
    {
        $player = new Player(5);
        $this->assertInstanceOf("\App\Card\Player", $player);

        $this->assertEquals("Player 5", $player->getPlayerName());
    }

    /**
     * Test changing player number.
     */
    public function testChangingPlayerNumber()
    {
        $player = new Player(5);

        $this->assertEquals("Player 5", $player->getPlayerName());

        $player->setNumber(10);

        $this->assertEquals("Player 10", $player->getPlayerName());
    }
}
