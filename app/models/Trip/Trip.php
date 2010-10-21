<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class representing trip with origin, destination and steps.
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

}