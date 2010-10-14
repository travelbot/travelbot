<?php

/**
 * Class representing one step (direction) of a trip.
 * @entity 
 */
class Step extends SimpleEntity
{

	/**
	 * Metres.	
	 * @param int
	 * @column(type="integer")	 
	 */
	private $distance;
	
	/**
	 * Seconds.
	 * @param int
	 * @column(type="integer")	 
	 */
	private $duration;
	
	/**
	 * Instructions text (optionally with HTML).
	 * @param string
	 * @column	 
	 */
	private $instructions;
	
	/**
	 * @var Trip	
	 * @manyToOne(targetEntity="Trip", inversedBy="steps", cascade={"persist"})
	 */
	private $trip;
	
	/**
	 * @column(type="integer")
	 * @var integer	 
	 */
	private $sequenceOrder = 0;
	
	/**
	 * @param int
	 * @param int
	 * @param string	 	 
	 */
	public function __construct($distance, $duration, $instructions)
	{
		$this->setDistance($distance)
			->setDuration($duration)
			->setInstructions($instructions);
	}
	
	/**
	 * @return int
	 */
	public function getDistance()
	{
		return $this->distance;
	}
	
	/**
	 * @param int
	 * @return Step Fluent interface	 
	 */
	public function setDistance($distance)
	{
		$this->distance = (int) $distance;
		return $this; // fluent interface
	}
	
	/**
	 * @return int
	 */
	public function getDuration()
	{
		return $this->duration;
	}
	
	/**
	 * @param int
	 * @return Step Fluent interface	 
	 */
	public function setDuration($duration)
	{
		$this->duration = (int) $duration;
		return $this; // fluent interface
	}
	
	/**
	 * @return string
	 */
	public function getInstructions()
	{
		return $this->instructions;
	}
	
	/**
	 * @param string
	 * @return Step Fluent interface	 
	 */
	public function setInstructions($instructions)
	{
		$this->instructions = (string) $instructions;
		return $this; // fluent interface
	}
	
	/**
	 * @return Trip
	 */
	public function getTrip()
	{
		return $this->trip;
	}
	
	/**
	 * @param Trip|NULl
	 * @return Step Fluent interface	 
	 */
	public function setTrip(Trip $trip = NULL)
	{
		if ($this->trip != NULL) {
			$this->trip->removeStep($this);
		}

		if ($trip != NULL) {
			$trip->addStep($this);
		}
		
		$this->trip = $trip;
		return $this; // fluent interface
	}
	
	/**
	 * @return int
	 */
	public function getSequenceOrder()
	{
		return $this->sequenceOrder;
	}
	
	/**
	 * @param int
	 * @return Step	 
	 */
	public function setSequenceOrder($order)
	{
		$this->sequenceOrder = $order;
		return $this; // fluent interface
	}

}