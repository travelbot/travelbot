<?php

/**
 * Class representing flight trip
 *
 * @author Petr
 */
class FlightTrip
{
    private $price;
    private $currency;
    private $book;
    private $legs;

    public function __construct($price, $currency, $book, $legs)
    {
        $this->price = $price;
        $this->currency = $currency;
        $this->book = $book;
        $this->legs = $legs;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getBook()
    {
        return $this->book;
    }

    public function getLegs()
    {
        return $this->legs;
    }

    protected function setPrice($price)
    {
        $this->price = $price;
    }

    protected function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    protected function setBook($book)
    {
        $this->book = $book;
    }

    protected function setLegs($legs)
    {
        $this->legs = $legs;
    }

}

