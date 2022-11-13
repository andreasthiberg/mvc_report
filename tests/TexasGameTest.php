<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class TexasGame.
 */
class TexasGameTest extends TestCase
{
    /**
     * Construct object and verify it.
     */
    public function testCreateObject()
    {
        $game = new TexasGame();
        $this->assertInstanceOf("\App\Card\TexasGame", $game);
    }

    /**
     * Test dealing and getting cards
     */
    public function testGettingCards()
    {
        $game = new TexasGame();
        $game->dealHands();
        $game->dealTableCards();
        $game->dealTableCards();
        $game->dealTableCards();

        $userCards = $game->getUserCards();
        $bankCards = $game->getBankCards();
        $tableCards = $game->getTableCards();

        $this->assertInstanceOf("\App\Card\Card", $userCards[0]);
        $this->assertInstanceOf("\App\Card\Card", $bankCards[0]);
        $this->assertInstanceOf("\App\Card\Card", $tableCards[0]);
    }

    /**
     * Test making bet
     */
    public function testMakingBet()
    {
        $game = new TexasGame();
        $game->makeBet();
        $bet = $game->getCurrentBet();

        $this->assertEquals(20, $bet);
    }

    /**
     * Tests making game, giving out cards and then ending game and getting result array
     */
    public function testEndingGame()
    {
        $game = new TexasGame();
        $game->dealHands();
        $game->dealTableCards();
        $game->dealTableCards();
        $game->dealTableCards();
        $result = $game->endGame();

        $this->assertArrayHasKey("winner", $result);
    }
}

