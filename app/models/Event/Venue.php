<?php

class Venue extends Nette\Object
{

	private $name;
	
	private $url;
	
	public function __construct($name, $url)
	{
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

}
