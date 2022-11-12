<?php

namespace App\Card;

use App\Card\Player;
use App\Card\CardHand;
use App\Card\Deck;
use App\Card\TexasCalculatio;

/**
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 */

class TexasGame
{
    private Player $user;
    private Player $bank;
    private Deck $deck;
    private CardHand $tableCards;

    private int $currentBet;

    public function __construct()
    {
        $this->user = new Player(1);
        $this->bank = new Player(2);
        $this->tableCards = new CardHand();

        $this->deck = new Deck();
        $this->deck->shuffleDeck();

        $this->currentBet = 0;
    }

    /**
     *
     * Gets all cards for user (human player)
     *
     * @return array<Card>
    */
    public function getUserCards()
    {
        return $this->user->getHand()->getCards();
    }

    /**
     *
     * Gets all cards for bank
     *
     * @return array<Card>
    */
    public function getBankCards()
    {
        return $this->bank->getHand()->getCards();
    }

    /**
     *
     * Gets all cards on table
     *
     * @return array<Card>
    */
    public function getTableCards()
    {
        return $this->tableCards->getCards();
    }

    public function getCurrentBet(): int
    {
        return $this->currentBet;
    }

    /**
     *
     * Gives a (possibly specified) card to user
     *
    */
    public function giveCardToUser(Card $presetCard = null): void
    {
        //Optional preset cards as argument - otherwise draw
        if (is_null($presetCard)) {
            /**
             * @var array<Card> drawn cards
            */
            $drawnCards = $this->deck->drawCards(1);
        } else {
            $drawnCards = [$presetCard];
        }
        $this->user->giveCard($drawnCards[0]);
    }

    /**
     *
     * Gives a (possibly specified) card to bank
     *
    */
    public function giveCardToBank(Card $presetCard = null): void
    {
        //Optional preset cards as argument - otherwise draw
        if (is_null($presetCard)) {
            /**
             * @var array<Card> drawn cards
            */
            $drawnCards = $this->deck->drawCards(1);
        } else {
            $drawnCards = [$presetCard];
        }
        $this->bank->giveCard($drawnCards[0]);
    }

    /**
     *
     * Gives a (possibly specified) card to table cards
     *
    */
    public function giveCardToTable(Card $presetCard = null): void
    {
        //Optional preset cards as argument - otherwise draw
        if (is_null($presetCard)) {
            /**
             * @var array<Card> drawn cards
            */
            $drawnCards = $this->deck->drawCards(1);
        } else {
            $drawnCards = [$presetCard];
        }
        $this->tableCards->addCard($drawnCards[0]);
    }


    /**
     *
     * Deals initial 2 cards to both players
     *
    */
    public function dealHands(): void
    {
        $this->giveCardToBank();
        $this->giveCardToBank();
        $this->giveCardToUser();
        $this->giveCardToUser();
    }

    /**
     *
     * Puts 3 or 1 cards new cards on table
     *
    */
    public function dealTableCards(): void
    {
        $tableCardCount = count($this->tableCards->getCards());
        if ($tableCardCount == 0) {
            $this->giveCardToTable();
            $this->giveCardToTable();
            $this->giveCardToTable();
        } elseif ($tableCardCount < 5) {
            $this->giveCardToTable();
        }
    }

    /**
     *
     * Bets 20 money for the player
     *
    */
    public function makeBet(): void
    {
        $this->currentBet += 20;
    }

    /**
     *
     * Ends game calculates winner and pays out bet on win.
     * @return mixed[]
     *
    */
    public function endGame()
    {
        $calc = new TexasCalculation();
        $comparisonResults = $calc->compareTexasHands(
            $this->bank->getHand(),
            $this->user->getHand(),
            $this->tableCards
        );
        return $comparisonResults;
    }
}
