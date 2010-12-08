<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author mirteond
 *
 * @entity
 * @table(name="poi")
 */

class Poi extends SimpleEntity {

        /**
         * @var string
         * @column
         */
        private $name;

        /**
        * @var string
        * @column
        */
        private $types;

        /**
        * @var string
        * @column
        */
        private $address;

        /**
         * @var float
         * @column(type="decimal")
         */
        private $latitude;

         /**
         * @var float
         * @column(type="decimal")
         */
        private $longitude;

         /**
         * @var string
         * @column
         */
        private $url;

         /**
         * @var string
         * @column
         */
        private $imageUrl;

         /**
	      * @var Doctrine\Common\Collections\ArrayCollection
          * @ManyToMany(targetEntity="Trip", mappedBy="pois",cascade={"persist"})
         */
        private $trips;
        
        /**
         * @var PoiGroup
         * @manyToOne(targetEntity="PoiGroup", inversedBy="pois", cascade={"persist"})         
         */		         
        private $group;


        public function __construct() {
		$this->trips = new ArrayCollection;
	}

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

        /**
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
        public function getTrips()
	{
		return $this->trips;
	}

	/**
	 * @param Trip
	 * @return Poi Fluent interface
	 */
	public function addTrip (Trip $trip)
	{
		if (!$this->trips->contains($trip)) {
			$this->trips->add($trip);
			$trip->addPoi($this);
		}

		return $this; // fluent interface
	}

	/**
	 * @param Trip
	 * @return Poi Fluent interface
	 */
	public function removeTrip(Trip $trip)
	{
		if ($this->trips->contains($trip)) {
			$this->trips->removeElement($trip);
			$trip->removePoi($this);
		}

		return $this; // fluent interface
	}
	
	public function getGroup()
	{
		return $this->group;
	}
	
	public function setGroup(PoiGroup $group)
	{
		$this->group = $group;
		$group->addPoi($this);
		
		return $this;
	}
}

