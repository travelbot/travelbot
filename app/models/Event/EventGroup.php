<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Entity for grouping events by search terms.
 * @author ondrej
 * @entity
 * @table(name="eventgroup") 
 */
class EventGroup extends SimpleEntity
{

	/**
	 * @var string
	 * @column	 
	 */
	private $location;
	
	/**
	 * @var DateTime
	 * @column(type="datetimetz")	 
	 */
	private $date;
	
	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @oneToMany(targetEntity="Event", mappedBy="group", cascade={"persist"})	 
	 */
	private $events;
	
	public function __construct()
	{
		$this->events = new ArrayCollection;
	}
	
	public function getLocation()
	{
		return $this->location;
	}
	
	public function setLocation($location)
	{
		$this->location = $location;
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
	
	public function getEvents()
	{
		return $this->events;
	}
	
	public function addEvent(Event $event)
	{
		if (!$this->events->contains($event)) {
			$this->events->add($event);
			$event->group = $this;
		}
		
		return $this;
	}
	
	public function removeEvent(Event $event)
	{
		if ($this->events->contains($event)) {
			$this->events->removeElement($event);
			$event->group = NULL;
		}
		
		return $this;
	}

}
