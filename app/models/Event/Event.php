<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class representing trip with origin, destination and steps.
 * @author javi.cullera
 *
 * @entity
 * @table(name="event")
 */
class Event extends SimpleEntity
{
        /**
	 * @var string
	 * @column
	 */
	private $title;
	/**
	 * @var string
	 * @column
	 */
	private $url;
	/**
	 * @var string
	 * @column
	 */
	private $description;
	/**
	 * @var DateTime
	 * @column(type="datetimetz")
	 */
	private $date;

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
	 * @var Venue
	 * @manyToOne(targetEntity="Venue", inversedBy="events", cascade={"persist"})
	 */
	private $venue;

        /**
	 * @var Doctrine\Common\Collections\ArrayCollection
         * @ManyToMany(targetEntity="Trip", mappedBy="events",cascade={"persist"})
	 * orderBy({sequenceOrder="ASC"})
        */
	private $trips;
        

        public function __construct() {
		$this->trips = new ArrayCollection;
	}

	public function getTitle()
	{
		return $this->title;
	}
	
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}


        public function getLatitude()
	{
		return $this->latitude;
	}

	public function setLatitude($latitude)
	{
		$this->latitude = $latitude;
		return $this;
	}

         public function getLongitude()
	{
		return $this->longitude;
	}

	public function setLongitude($longitude)
	{
		$this->longitude = $longitude;
		return $this;
	}


	public function getUrl()
	{
		return $this->url;
	}
	
	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}
	
	public function getDate()
	{
		return $this->date;
	}
	
	public function setDate(DateTime $date)
	{
		$this->date = $date;
		return $this;
	}
	 /**
	 * @return Venue Fluent interface
	 */
	public function getVenue()
	{
		return $this->venue;
	}
	/**
	 * @param Venue|NULl
	 * @return Event Fluent interface
	 */
	public function setVenue(Venue $venue)
	{
		$this->venue = $venue;
		return $this;
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
	 * @return Event Fluent interface
	 */
	public function addTrip (Trip $trip)
	{
		if (!$this->trips->contains($trip)) {
			$this->trips->add($trip);
                        if (!$trip->events->contains($this)) 
                            $trip->events->add ($this);
		}

		return $this; // fluent interface
	}

	/**
	 * @param Trip
	 * @return Event Fluent interface
	 */
	public function removeTrip(Trip $trip)
	{
		if ($this->trips->contains($trip)) {
			$this->trips->removeElement($trip);
                        if ($trip->events->contains($this)) 
                            $trip->events->removeElement($this);
                }

		return $this; // fluent interface
	}



	

}
