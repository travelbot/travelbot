<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author mirteond 
 *  
 * @entity
 * @table(name="trip")  
 */ 
class Trip extends SimpleEntity
{

	/**
	 * @var string
	 * @column	 
	 */
	private $departure;
	
	/**
	 * @var string
	 * @column	 
	 */
	private $arrival;
	
	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @oneToMany(targetEntity="Step", mappedBy="trip", cascade={"persist"})
	 * orderBy({sequenceOrder="ASC"})
	 */

	private $steps;

    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     * @ManyToMany(targetEntity="Event", inversedBy="trips", cascade={"persist"})
     * @JoinTable(name="event_trip",
     *   joinColumns={@JoinColumn(name="trip_id", referencedColumnName="id")},
     *   inverseJoinColumns={@JoinColumn(name="event_id", referencedColumnName="id")}
     * )
    */
    private $events;

      
     /**
      * @var Doctrine\Common\Collections\ArrayCollection
      * @ManyToMany(targetEntity="Poi", inversedBy="trips", cascade={"persist"})
      * @JoinTable(name="poi_trip",
      *   joinColumns={@JoinColumn(name="trip_id", referencedColumnName="id")},
      *   inverseJoinColumns={@JoinColumn(name="poi_id", referencedColumnName="id")}
      * )
      */
	private $pois;

	/**
	 * @var int
	 */
	private $lastSequenceOrder = 0;
	
	/**
	 * @param string
	 * @param string
	 * @param array	 	 
	 */
	public function __construct($departure, $arrival, array $steps = array()) {
		$this->departure = (string) $departure;
		$this->arrival = (string) $arrival;
		
		$this->steps = new ArrayCollection;
                $this->events = new ArrayCollection;
                $this->pois = new ArrayCollection;
		
		foreach($steps as $step) {
			$this->addStep($step);	
		}
		
		if ($this->steps->last() != NULL) {
			$this->lastSequenceOrder = $this->steps->last()->sequenceOrder;
		}
	}
	
	/**
	 * Counted distance (from steps).
	 * @return int Metres	 
	 */
	public function getDistance()
	{
		$distance = 0;
		foreach($this->steps as $step) {
			$distance += $step->distance;
		}
		return $distance;
	}
	
	/**
	 * Counted duration (from steps).
	 * @return int Seconds	 
	 */
	public function getDuration()
	{
		$duration = 0;
		foreach($this->steps as $step) {
			$duration += $step->duration;
		}
		return $duration;
	}
	
	/**
	 * @return string
	 */
	public function getDeparture()
	{
		return $this->departure;
	}
	
	/**
	 * @return string
	 */
	public function getArrival()
	{
		return $this->arrival;
	}
	
	/**
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getSteps()
	{
		return $this->steps;
	}

	/**
	 * @param Step
	 * @return Trip Fluent interface
	 */
	public function addStep(Step $step)
	{
		if (!$this->steps->contains($step)) {
			$step->sequenceOrder = ++$this->lastSequenceOrder;
			$this->steps->add($step);
			$step->trip = $this;
		}

		return $this; // fluent interface
	}

	/**
	 * @param Step
	 * @return Trip Fluent interface
	 */
	public function removeStep(Step $step)
	{
		if ($this->steps->contains($step)) {
			$this->steps->removeElement($step);
			$step->trip = NULL;
		}

		return $this; // fluent interface
	}

        /**
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
 	public function getEvents()
	{
		return $this->events;
	}

	/**
	 * @param Event
	 * @return Trip Fluent interface
	 */
	public function addEvent(Event $event)
	{
		if (!$this->events->contains($event)) {
			$this->events->add($event);
            $event->addTrip($this);
        }

		return $this; // fluent interface
	}

	/**
	 * @param Event
	 * @return Trip Fluent interface
	 */
	public function removeEvent(Event $event)
	{
		if ($this->events->contains($event)) {
			$this->events->removeElement($event);
            $event->removeTrip($this);
		}

		return $this; // fluent interface
	}

        /**
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getPois()
	{
		return $this->pois;
	}

	/**
	 * @param Poi
	 * @return Trip Fluent interface
	 */
	public function addPoi(Poi $poi)
	{
		if (!$this->pois->contains($poi)) {
			$this->pois->add($poi);
			$poi->addTrip($this);
		}

		return $this; // fluent interface
	}

	/**
	 * @param   Poi
	 * @return Trip Fluent interface
	 */
	public function removePoi(Poi $poi)
	{
		if ($this->pois->contains($poi)) {
			$this->pois->removeElement($poi);
			$poi->removeTrip($this);
		}

		return $this; // fluent interface
	}
     
}