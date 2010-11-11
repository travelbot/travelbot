<?php

/**
 * Description of PointOfInterest
 *
 * @author Petr Vales
 */
class Poi extends Nette\Object {

    private $name;
    private $types;
    private $address;
    private $latitude;
    private $longitude;
    private $url;
    private $imageUrl;

    public function getName()   {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getTypes()   {
        return $this->types;
    }

    public function setTypes($types) {
        $this->types = $types;
    }

    public function getAddress()   {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getLatitude()   {
        return $this->latitude;
    }

    public function setLatitude($latitude) {
        $this->latitude = (float) $latitude;
    }

    public function getLongitude()   {
        return $this->longitude;
    }

    public function setLongitude($longitude) {
        $this->longitude = (float) $longitude;
    }

    public function getUrl()   {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }
    
    public function getImageUrl()
    {
		return $this->imageUrl;
	}
	
	public function setImageUrl($url)
	{
		$this->imageUrl = $url;
	}

}

