<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class TexasCalculation.
 */
class TexasCalculationTest extends TestCase
{
    /**
     * Try comparing two hands (nothing and flush)
     */
    public function testCompareHands1()
    {
        $firstHoleHand = new CardHand();
        $firstHoleHand->addCard(new Card("Clubs",10));
        $firstHoleHand->addCard(new Card("Clubs",8));

        $secondHoleHand = new CardHand();
        $secondHoleHand->addCard(new Card("Hearts",7));
        $secondHoleHand->addCard(new Card("Hearts",7));

        $tableHand = new CardHand();
        $tableHand->addCard(new Card("Clubs",1));
        $tableHand->addCard(new Card("Hearts",5));
        $tableHand->addCard(new Card("Hearts",7));
        $tableHand->addCard(new Card("Clubs",12));
        $tableHand->addCard(new Card("Hearts",13));

        $calc = new TexasCalculation();

        $result = $calc->compareTexasHands($firstHoleHand,$secondHoleHand,$tableHand);
        $expectedResult = array ("winner" => 2, "first_player_points" => 0, "second_player_points" => 5, "won_by_rank" => False,
        "first_player_combination" => "inget", "second_player_combination" => "färg");

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Try comparing two hands (pair and two pair)
     */
    public function testCompareHands2()
    {
        $firstHoleHand = new CardHand();
        $firstHoleHand->addCard(new Card("Clubs",10));
        $firstHoleHand->addCard(new Card("Clubs",7));

        $secondHoleHand = new CardHand();
        $secondHoleHand->addCard(new Card("Clubs",1));
        $secondHoleHand->addCard(new Card("Hearts",7));

        $tableHand = new CardHand();
        $tableHand->addCard(new Card("Clubs",10));
        $tableHand->addCard(new Card("Hearts",5));
        $tableHand->addCard(new Card("Hearts",7));
        $tableHand->addCard(new Card("Clubs",12));
        $tableHand->addCard(new Card("Hearts",13));

        $calc = new TexasCalculation();

        $result = $calc->compareTexasHands($firstHoleHand,$secondHoleHand,$tableHand);
        $expectedResult = array ("winner" => 1, "first_player_points" => 2, "second_player_points" => 1, "won_by_rank" => False,
        "first_player_combination" => "två par", "second_player_combination" => "par");

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Try comparing two hands (straight and straight flush)
     */
    public function testCompareHands3()
    {
        $firstHoleHand = new CardHand();
        $firstHoleHand->addCard(new Card("Clubs",10));
        $firstHoleHand->addCard(new Card("Clubs",9));

        $secondHoleHand = new CardHand();
        $secondHoleHand->addCard(new Card("Hearts",9));
        $secondHoleHand->addCard(new Card("Hearts",10));

        $tableHand = new CardHand();
        $tableHand->addCard(new Card("Clubs",11));
        $tableHand->addCard(new Card("Hearts",5));
        $tableHand->addCard(new Card("Hearts",7));
        $tableHand->addCard(new Card("Clubs",12));
        $tableHand->addCard(new Card("Clubs",13));

        $calc = new TexasCalculation();

        $result = $calc->compareTexasHands($firstHoleHand,$secondHoleHand,$tableHand);
        $expectedResult = array ("winner" => 1, "first_player_points" => 8, "second_player_points" => 4, "won_by_rank" => False,
        "first_player_combination" => "färgstege", "second_player_combination" => "stege");

        $this->assertEquals($expectedResult, $result);
    }


    /**
     * Try comparing two hands (3 of a kind and full house)
     */
    public function testCompareHands4()
    {
        $firstHoleHand = new CardHand();
        $firstHoleHand->addCard(new Card("Clubs",10));
        $firstHoleHand->addCard(new Card("Clubs",9));

        $secondHoleHand = new CardHand();
        $secondHoleHand->addCard(new Card("Hearts",9));
        $secondHoleHand->addCard(new Card("Hearts",11));

        $tableHand = new CardHand();
        $tableHand->addCard(new Card("Spades",9));
        $tableHand->addCard(new Card("Diamonds",9));
        $tableHand->addCard(new Card("Hearts",7));
        $tableHand->addCard(new Card("Hearts",10));
        $tableHand->addCard(new Card("Diamonds",1));

        $calc = new TexasCalculation();

        $result = $calc->compareTexasHands($firstHoleHand,$secondHoleHand,$tableHand);
        $expectedResult = array ("winner" => 1, "first_player_points" => 6, "second_player_points" => 3, "won_by_rank" => False,
        "first_player_combination" => "kåk", "second_player_combination" => "triss");

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Try comparing two hands (both with four of a kind)
     */
    public function testCompareHands5()
    {
        $firstHoleHand = new CardHand();
        $firstHoleHand->addCard(new Card("Clubs",14));
        $firstHoleHand->addCard(new Card("Clubs",9));

        $secondHoleHand = new CardHand();
        $secondHoleHand->addCard(new Card("Hearts",9));
        $secondHoleHand->addCard(new Card("Hearts",11));

        $tableHand = new CardHand();
        $tableHand->addCard(new Card("Spades",13));
        $tableHand->addCard(new Card("Diamonds",13));
        $tableHand->addCard(new Card("Hearts",13));
        $tableHand->addCard(new Card("Hearts",13));
        $tableHand->addCard(new Card("Diamonds",12));

        $calc = new TexasCalculation();

        $result = $calc->compareTexasHands($firstHoleHand,$secondHoleHand,$tableHand);
        $expectedResult = array ("winner" => 1, "first_player_points" => 7, "second_player_points" => 7, "won_by_rank" => True,
        "first_player_combination" => "fyrtal", "second_player_combination" => "fyrtal");


        //Switch places in method
        $result = $calc->compareTexasHands($secondHoleHand,$firstHoleHand,$tableHand);
        $expectedResult = array ("winner" => 2, "first_player_points" => 7, "second_player_points" => 7, "won_by_rank" => True,
        "first_player_combination" => "fyrtal", "second_player_combination" => "fyrtal");

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Try comparing two hands (both with pairs but different ranks)
     */
    public function testCompareHands6()
    {
        $firstHoleHand = new CardHand();
        $firstHoleHand->addCard(new Card("Clubs",13));
        $firstHoleHand->addCard(new Card("Clubs",9));

        $secondHoleHand = new CardHand();
        $secondHoleHand->addCard(new Card("Hearts",9));
        $secondHoleHand->addCard(new Card("Hearts",11));

        $tableHand = new CardHand();
        $tableHand->addCard(new Card("Spades",13));
        $tableHand->addCard(new Card("Diamonds",11));
        $tableHand->addCard(new Card("Hearts",6));
        $tableHand->addCard(new Card("Hearts",2));
        $tableHand->addCard(new Card("Diamonds",12));

        $calc = new TexasCalculation();

        $result = $calc->compareTexasHands($firstHoleHand,$secondHoleHand,$tableHand);
        $expectedResult = array ("winner" => 1, "first_player_points" => 1, "second_player_points" => 1, "won_by_rank" => True,
        "first_player_combination" => "par", "second_player_combination" => "par");;

        //Switched parameters in method
        $result = $calc->compareTexasHands($secondHoleHand,$firstHoleHand,$tableHand);
        $expectedResult = array ("winner" => 2, "first_player_points" => 1, "second_player_points" => 1, "won_by_rank" => True,
        "first_player_combination" => "par", "second_player_combination" => "par");;

        $this->assertEquals($expectedResult, $result);
    }
}
