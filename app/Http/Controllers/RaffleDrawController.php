<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CalculateRaffleWinner;

class RaffleDrawController extends Controller
{
    public function draw(Request $request)
    {
        $raffle = (new CalculateRaffleWinner())
        ->fibonacci()
        ->selectRaffleWinners()
        ->saveRaffleWinners();
        return response($raffle->raffleWinners,201);
    }

    public function checkRaffleNumber(Request $request)
    {
        $fields=$request->validate([
            'number'=>'required|integer',
            'country'=>'required|string'
        ]);
        $raffle = (new CalculateRaffleWinner())
        ->fibonacci()
        ->checkIfNumberisValid($request->number)
        ->checkIfDrawHasTakenPlace()
        ->checkIfUserIsWinner($request->number);
       

        if(!$raffle->validNumber){
            return response(['message'=>'Number is invalid.'],401);
        }
        if(!$raffle->hasRaffleDraw){
            return response(['message'=>'Raffle has not happened.'],401);
        }
        if($raffle->userIsWinner){
            $raffle->getCountryCurrency($request->country)
            ->ConvertCurrency();
            return response(['message'=>'Congratulations!!! You have won '.$raffle->currency.' '.$raffle->prizeInLocal],201);
        }else{
            return response(['message'=>'Sorry! You have not won'],201);
        }
    }
}
