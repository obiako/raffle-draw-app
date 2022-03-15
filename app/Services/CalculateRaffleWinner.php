<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\RaffleDraw;
use AmrShawky\LaravelCurrency\Facade\Currency;
use PragmaRX\Countries\Package\Countries;

class CalculateRaffleWinner{

    public  $fibonacciMax = 1000;
    public  $fibonacciSequence = [];
    public int $noOfRaffleWinners=3;
    public $raffleWinners = [];
    public bool $validNumber = false;
    public bool $hasRaffleDraw = false;
    public bool $userIsWinner = false;
    public string $currency;
    public int $prizeInDollar=2;
    public float $prizeInLocal;


    public function __constructor__(): void
    {
        $this->fibonacci();
    }

    public function fibonacci():self
    {
  
        $num1 = 1;
        $num2 = 2;
        $sequence=[];
      
        $counter = 0;
        while ($num2 < $this->fibonacciMax){
            $sequence[]=$num1;
            $num3 = $num2 + $num1;
            $num1 = $num2;
            $num2 = $num3;
            $counter = $counter + 1;
        }
        $this->fibonacciSequence=$sequence;
        

        return $this;
    }

    public function selectRaffleWinners():self
    {
        $randomKeys= array_rand($this->fibonacciSequence, $this->noOfRaffleWinners);
        foreach($randomKeys as $randomKey){
            $this->raffleWinners[]=$this->fibonacciSequence[$randomKey];
        }

        return $this;
    }

    public function saveRaffleWinners():self
    {
        RaffleDraw::create(['winners'=>$this->raffleWinners]);
        return $this;
    }

    public function checkIfNumberisValid($number):self
    {
        if(in_array($number,$this->fibonacciSequence)){
            $this->validNumber=true;
        }

       return $this;
    }

    public function checkIfDrawHasTakenPlace()
    {
        $draw=RaffleDraw::get()->first();
        if($draw){
            $this->hasRaffleDraw=true;
        }
        return $this;
    }

    public function checkIfUserIsWinner($number)
    {
        $draw=RaffleDraw::latest()->first();
        if($draw){
            $this->raffleWinners=$draw->winners;
            if(in_array($number,$this->raffleWinners)){
                $this->userIsWinner=true;
            }

        }
        return $this;
    }

    public function getCountryCurrency($name='Nigeria')
    {
        $countries = new Countries();
        $country=$countries->whereNameCommon($name);
        if($country!=null){
            $this->currency=$country->pluck('currencies')[0][0];
           
        }
        return $this;
        

    }
    public function ConvertCurrency()
    {
        $amount = Currency::convert()
        ->from('USD')
        ->to($this->currency)
        ->amount($this->prizeInDollar)
        ->round(2)
        ->get();
        $this->prizeInLocal=$amount;
        return $this;
    }

    public function __toString()
    {
        
        return json_encode(['fibonacciMax'=>$this->fibonacciMax,'noOfRaffleWinners'=>$this->noOfRaffleWinners,'validNumber'=>$this->validNumber,
    'hasRaffleDraw'=>$this->hasRaffleDraw,'userIsWinner'=>$this->userIsWinner]);
    }





}
