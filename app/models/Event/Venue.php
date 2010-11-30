<?php

use Doctrine\Common\Collections\ArrayCollection;
/**
 * @author mirteond
 *
 * @entity
 * @table(name="venue")
 */

class Venue extends SimpleEntity
{	
        /**
         * @var string
         * @column
         */
        private $name;


        /**
         * @var string
         * @column
         */
	private $url;

	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @oneToMany(targetEntity="Event", mappedBy=" venue", cascade={"persist"})
	 * orderBy({sequenceOrder="ASC"})
	 */
	private $events;
	
	public function __construct($name, $url)
	{
                $this->events = new ArrayCollection;
		$this->setName($name);
		$this->setUrl($url);
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
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

         /**
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
        public function getEvents()
	{
		return $this->events;
	}

	/**
	 * @param Event
	 * @return Event Fluent interface
	 */
	public function addEvent (Event $event)
	{
		if (!$this->events->contains($event)) {
			$this->events->add($event);
                        $event->venue= $this;
		}

    		return $this; // fluent interface
	}

	/**
	 * @param Event
	 * @return Venue Fluent interface
	 */
	public function removeEvent(Event $event)
	{
		if ($this->events->contains($event)) {                       
			$this->events->removeElement($event);
                }

		return $this; // fluent interface
	}



}
