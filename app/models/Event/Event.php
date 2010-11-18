<?php

class Event extends Nette\Object
{

	private $title;
	
	private $url;
	
	private $description;
	
	private $date;
	
	private $venue;
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function setTitle($title)
	{
		$this->title = $title;
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
	
	public function getVenue()
	{
		return $this->venue;
	}
	
	public function setVenue(Venue $venue)
	{
		$this->venue = $venue;
		return $this;
	}
	

}