<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class representing trip with origin, destination and steps. 
 */ 
class Trip extends SimpleEntity
{

	/**
	 * @var string
	 */
	private $from;
	
	/**
	 * @var string
	 */
	private $to;
	
	/**
	 * @var array
	 */
	private $steps;
	
	/**
	 * @param string
	 * @param string
	 * @param array	 	 
	 */
	public function __construct($from, $to, array $steps = array()) {
		$this->from = (string) $from;
		$this->to = (string) $to;
		
		$this->steps = new ArrayCollection($steps);
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
	public function getFrom()
	{
		return $this->from;
	}
	
	/**
	 * @return string
	 */
	public function getTo()
	{
		return $this->to;
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
			$this->steps->add($step);
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
		}
		
		return $this; // fluent interface
	}

}