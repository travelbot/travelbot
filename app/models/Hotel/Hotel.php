<?php

/**
 * Description of Hotel
 *
 * @author Petr
 */
class Hotel
{

    private $currency;
    private $link;
    private $totalPrice;
    private $amenities;
    private $lodgingType;
    private $starRating;

    public function __construct($currency, $link, $totalPrice, $amenities,
            $lodgingType, $starRating)
    {
        $this->setCurrency($currency)
                ->setLink($link)
                ->setTotalPrice($totalPrice)
                ->setAmenities($amenities)
                ->setLodgingType($lodgingType)
                ->setStarRating($starRating);
    }

    private function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    private function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    private function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }

    private function setAmenities($amenities)
    {
        $this->amenities = $amenities;
        return $this;
    }

    private function setLodgingType($lodgingType)
    {
        $this->lodgingType = $lodgingType;
        return $this;
    }

    private function setStarRating($starRating)
    {
        $this->starRating = $starRating;
        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    public function getAmenities()
    {
        return $this->amenities;
    }

    public function getLodgingType()
    {
        return $this->lodgingType;
    }

    public function getStarRating()
    {
        return $this->starRating;
    }

}

