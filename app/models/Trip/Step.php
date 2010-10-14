<?php

/**
 * Class representing one step (direction) of a trip.
 */
class Step extends Nette\Object
{

	/**
	 * Metres.	
	 * @param int
	 */
	private $distance;
	
	/**
	 * Seconds.
	 * @param int	 
	 */
	private $duration;
	
	/**
	 * Instructions text (optionally with HTML).
	 * @param string	 
	 */
	private $instructions;
	
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

}