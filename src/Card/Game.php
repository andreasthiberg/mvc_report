<?php

namespace App\Card;

use App\Card\Player;
use App\Card\Deck;

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

    public function getUserCards(){
        return $this->user->getHand()->getCards();
    }

    public function getBankCards(){
        return $this->bank->getHand()->getCards();
    }

    public function getUserPoints(){
        return $this->userPoints;
    }

    public function getBankPoints(){
        return $this->bankPoints;
    }

    public function drawUserCard(){
        $drawnCards = $this->deck->drawCards(1);
        $this->user->giveCard($drawnCards[0]);
        $this->userPoints = $this->user->getHand()->getPoints();
        if($this->userPoints == 21){
            $this->turn = "bank";
        }
        if($this->userPoints > 21){
            $this->winner = "bank";
        }
    }

    public function getWinner(){
        return $this->winner;
    }

    public function getTurn(){
        return $this->turn;
    }

    public function userStop(){
        $this->turn = "bank";
    }

    public function drawBankCard(){
        $drawnCards = $this->deck->drawCards(1);
        $this->bank->giveCard($drawnCards[0]);
        $this->bankPoints = $this->bank->getHand()->getPoints();
        if($this->bankPoints == 21){
            $this->winner = "bank";
        } else if($this->bankPoints > 21){
            $this->winner = "user";
        } else if($this->bankPoints >= 17){
            if($this->userPoints <= $this->bankPoints){
                $this->winner = "bank";
            } else {
                $this->winner = "user";
            }
        }
    }
}
