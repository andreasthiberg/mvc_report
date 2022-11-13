<?php

namespace App\Card;

use App\Card\Player;
use App\Card\Deck;
use App\Card\GameCalculation;

/**
 * Class representing a game of 21
 * @SuppressWarnings(PHPMD.ElseExpression)
 */

class Game
{
    private Player $user;
    private Player $bank;
    private Deck $deck;
    private int $userPoints;
    private int $bankPoints;
    private string $turn;
    private string $winner;

    public function __construct()
    {
        $this->user = new Player(1);
        $this->bank = new Player(2);

        $this->deck = new Deck();
        $this->deck->shuffleDeck();

        $this->userPoints = 0;
        $this->bankPoints = 0;

        $this->turn = "user";
        $this->winner = "";
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
     * Gets total user points
    */
    public function getUserPoints(): int
    {
        return $this->userPoints;
    }

    /**
     *
     * Gets total bank points
    */
    public function getBankPoints(): int
    {
        return $this->bankPoints;
    }

    /**
     *
     * Gets game winner
    */
    public function getWinner(): string
    {
        return $this->winner;
    }

    /**
     *
     * Gets string representing whose turn it is is
    */
    public function getTurn(): string
    {
        return $this->turn;
    }

    /**
     * End user's card drawing
    */
    public function userStop(): void
    {
        $this->turn = "bank";
    }

    /**
     * Draws a card for the bank
    */
    public function drawBankCard(Card $presetCard = null): void
    {
        //Optional preset card as argument - otherwise draw
        if (is_null($presetCard)) {
            /**
             * @var array<Card> drawn cards
            */
            $drawnCards = $this->deck->drawCards(1);
        } else {
            $drawnCards = [$presetCard];
        }
        $this->bank->giveCard($drawnCards[0]);
        $this->bankPoints = $this->bank->getHand()->getPoints();

        $calc = new GameCalculation();
        $this->winner = $calc->calculateWinner($this->bankPoints, $this->userPoints);
    }

    /**
     * Draws a card for the user
    */
    public function drawUserCard(Card $presetCard = null): void
    {
        if (is_null($presetCard)) {
            /**
             * @var array<Card> drawn cards
            */
            $drawnCards = $this->deck->drawCards(1);
        } else {
            $drawnCards = [$presetCard];
        }

        $this->user->giveCard($drawnCards[0]);
        $this->userPoints = $this->user->getHand()->getPoints();
        if ($this->userPoints == 21) {
            $this->turn = "bank";
        }
        if ($this->userPoints > 21) {
            $this->winner = "bank";
        }
    }
}
