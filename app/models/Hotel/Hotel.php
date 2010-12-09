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
    private $pricePerNight;

    public function __construct($currency, $link, $totalPrice, $amenities,
            $lodgingType, $starRating, $pricePerNight)
    {
        $this->setCurrency($currency)
                ->setLink($link)
                ->setTotalPrice($totalPrice)
                ->setAmenities($amenities)
                ->setLodgingType($lodgingType)
                ->setStarRating($starRating)
                ->setPricePerNight($pricePerNight);
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
        $this->starRating = "";
        $floorRating = floor($starRating);
        for ($index = 0; $index < ($floorRating-1); $index++) {
            $this->starRating = $this->starRating . "☆";
        }
        if($starRating > $floorRating) {
            $this->starRating = $this->starRating . "★";
        } else {
            $this->starRating = $this->starRating . "☆";
        }
        return $this;
    }

    private function setPricePerNight($pricePerNight)
    {
        $this->pricePerNight = $pricePerNight;
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

    public function getPricePerNight()
    {
        return $this->pricePerNight;
    }

}

