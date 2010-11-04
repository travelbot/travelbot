<?php

/**
 * Description of PointOfInterest
 *
 * @author Petr Vales
 */
class PointOfInterest {

    private $name;
    private $vicinity;
    private $types;
    private $phoneNumber;
    private $address;
    private $lat;
    private $lng;
    private $rating;
    private $url;
    private $icon;
    private $reference;

    public function __construct($name, $vicinity, $types, $phoneNumber,
            $address, $lat, $lng, $rating, $url, $icon, $reference) {
        $this->name = $name;
        $this->vicinity = $vicinity;
        $this->types = $types;
        $this->phoneNumber = $phoneNumber;
        $this->address = $address;
        $this->lat = $lat;
        $this->lng = $lng;
        $this->rating = $rating;
        $this->url = $url;
        $this->icon = $icon;
        $this->reference =$reference;
    }

    public function getName()   {
        return $this->name;
    }

    private function setName($name) {
        $this->name = $name;
    }

    public function getVicinity()   {
        return $this->vicinity;
    }

    private function setVicinity($vicinity) {
        $this->vicinity = $vicinity;
    }

    public function getTypes()   {
        return $this->types;
    }

    private function setTypes($types) {
        $this->types = $types;
    }

    public function getPhoneNumber()   {
        return $this->phoneNumber;
    }

    private function setPhoneNumber($phoneNumber) {
        $this->phoneNumber = $phoneNumber;
    }

    public function getAddress()   {
        return $this->address;
    }

    private function setAddress($address) {
        $this->address = $address;
    }

    public function getLat()   {
        return $this->lat;
    }

    private function setLat($lat) {
        $this->lat = $lat;
    }

    public function getLng()   {
        return $this->lng;
    }

    private function setLng($lng) {
        $this->lng = $lng;
    }

    public function getRating()   {
        return $this->rating;
    }

    private function setRating($rating) {
        $this->rating = $rating;
    }

    public function getUrl()   {
        return $this->url;
    }

    private function setUrl($url) {
        $this->url = $url;
    }

    public function getIcon()   {
        return $this->icon;
    }

    private function setIcon($icon) {
        $this->icon = $icon;
    }

    public function getReference()   {
        return $this->reference;
    }

    private function setReference($reference) {
        $this->reference = $reference;
    }

}

