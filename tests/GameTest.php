<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Game.
 */
class GameTest extends TestCase
{
    /**
     * Construct object and verify it.
     */
    public function testCreateObject()
    {
        $game = new Game();
        $this->assertInstanceOf("\App\Card\Game", $game);
    }

    /**
     * Try drawing cards for user and bank.
     */
    public function testDrawCards()
    {
        //Checks that carhands are empty
        $game = new Game();
        $userCards = $game->getUserCards();
        $bankCards = $game->getBankCards();
        $this->assertEquals([],$bankCards);
        $this->assertEquals([],$userCards);

        //Checks that cards are added
        $game->drawUserCard();
        $game->drawBankCard(); 
        $userCards = $game->getUserCards();
        $bankCards = $game->getBankCards();
        $this->assertInstanceOf("\App\Card\Card",$bankCards[0]);
        $this->assertInstanceOf("\App\Card\Card",$userCards[0]);
    }


    /**
     * Try getting points for both players
     */
    public function testGetPoints()
    {
        $game = new Game();
        $game->drawUserCard();
        $game->drawBankCard(); 

        $this->assertGreaterThan(0,$game->getUserPoints());
        $this->assertGreaterThan(0,$game->getBankPoints());
    }

    /**
     * Try getting Winner and turn
     */
    public function testWinnerAndTurn()
    {
        $game = new Game();
        $this->assertEquals("",$game->getWinner());
        $this->assertEquals("user",$game->getTurn());
        $game->userStop();
        $this->assertEquals("bank",$game->getTurn());
    }

    /**
     * Check if turn changes when user gets 21
     */
    public function testGivingUser21()
    {
        // Create a stub for the Card class that always has rank 7.
        $stubCard = $this->createStub(Card::class);

        // Configure the stub.
        $stubCard->method('getRank')
            ->willReturn(7);

        $game = new Game();

        $this->assertEquals("user",$game->getTurn());

        $game->drawUserCard($stubCard);
        $game->drawUserCard($stubCard);
        $game->drawUserCard($stubCard);

        $this->assertEquals(21,$game->getUserPoints());
        $this->assertEquals("bank",$game->getTurn());
    }

    /**
     * Check if bank wins if user gets over 21
     */
    public function testGivingUserOver21()
    {
        // Create a stub for the Card class that always has rank 8.
        $stubCard = $this->createStub(Card::class);

        // Configure the stub.
        $stubCard->method('getRank')
            ->willReturn(8);

        $game = new Game();

        $this->assertEquals("",$game->getWinner());

        $game->drawUserCard($stubCard);
        $game->drawUserCard($stubCard);
        $game->drawUserCard($stubCard);

        $this->assertEquals(24,$game->getUserPoints());
        $this->assertEquals("bank",$game->getWinner());
    }

    /**
     * Check if bank wins if it gets 21
     */
    public function testGivingBank21()
    {
        // Create a stub for the Card class that always has rank 7.
        $stubCard = $this->createStub(Card::class);

        // Configure the stub.
        $stubCard->method('getRank')
            ->willReturn(7);

        $game = new Game();

        $this->assertEquals("",$game->getWinner());

        $game->drawBankCard($stubCard);
        $game->drawBankCard($stubCard);
        $game->drawBankCard($stubCard);

        $this->assertEquals(21,$game->getBankPoints());
        $this->assertEquals("bank",$game->getWinner());
    }


    /**
     * Check if user wins if bank gets over 21
     */
    public function testGivingBankOver21()
    {
        // Create a stub for the Card class that always has rank 8.
        $stubCard = $this->createStub(Card::class);

        // Configure the stub.
        $stubCard->method('getRank')
            ->willReturn(8);

        $game = new Game();

        $this->assertEquals("",$game->getWinner());

        $game->drawBankCard($stubCard);
        $game->drawBankCard($stubCard);
        $game->drawBankCard($stubCard);

        $this->assertEquals(24,$game->getBankPoints());
        $this->assertEquals("user",$game->getWinner());
    }

    /**
     * Check if bank wins if bank stops and wins at higher points (>17) than user
     */
    public function testGivingBankWinningPoinst()
    {
        // Create stubs
        $stubCard6 = $this->createStub(Card::class);
        $stubCard5 = $this->createStub(Card::class);

        // Configure the stub.
        $stubCard6->method('getRank')
            ->willReturn(6);
        $stubCard5->method('getRank')
            ->willReturn(5);

        $game = new Game();

        $this->assertEquals("",$game->getWinner());

        $game->drawUserCard($stubCard5);
        $game->drawUserCard($stubCard5);
        $game->drawUserCard($stubCard5);

        $game->drawBankCard($stubCard6);
        $game->drawBankCard($stubCard6);
        $game->drawBankCard($stubCard6);

        $this->assertEquals(15,$game->getUserPoints());
        $this->assertEquals(18,$game->getBankPoints());
        $this->assertEquals("bank",$game->getWinner());
    }

    /**
     * Check if bank wins if bank stops at (>17) and loses when user has a better hand
     */
    public function testGivingBankLosingPoints()
    {
        // Create stubs
        $stubCard6 = $this->createStub(Card::class);
        $stubCard7 = $this->createStub(Card::class);

        // Configure the stub.
        $stubCard6->method('getRank')
            ->willReturn(6);
        $stubCard7->method('getRank')
            ->willReturn(7);

        $game = new Game();

        $this->assertEquals("",$game->getWinner());

        $game->drawUserCard($stubCard7);
        $game->drawUserCard($stubCard7);
        $game->drawUserCard($stubCard7);

        $game->drawBankCard($stubCard6);
        $game->drawBankCard($stubCard6);
        $game->drawBankCard($stubCard6);

        $this->assertEquals(21,$game->getUserPoints());
        $this->assertEquals(18,$game->getBankPoints());
        $this->assertEquals("user",$game->getWinner());
    }

}
