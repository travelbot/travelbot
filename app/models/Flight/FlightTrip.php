<?php

/**
 * Class representing flight trip
 *
 * @author Petr
 */
class FlightTrip {
    private $price;
    private $legs;

    public function  __construct($price, $legs) {
        $this->price = $price;
        $this->legs = $legs;
    }

    public function getPrice()  {
        return $this->price;
    }

    public function getLegs()   {
        return $this->legs;
    }

    protected  function setPrice($price)    {
        $this->price = $price;
    }

    protected  function setLegs($legs)  {
        $this->legs = $legs;
    }

}

