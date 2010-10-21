<?php

/**
 * Class representing a location in the world.
 * @author mirteond 
 */
class Location extends Nette\Object
{

	/**
	 * @var string
	 */
	private $street;
	
	/**
	 * @var string
	 */	 	
	private $city;
	
	/**
	 * @var string
	 */
	private $country;
	
	/**
	 * @param string
	 * @param string
	 * @param string	 	 
	 */
	public function __construct($street, $city, $country)
	{
		$this->street = (string) $street;
		$this->city = (string) $city;
		$this->country = (string) $country;
	}
	
	/**
	 * @return string
	 */
	public function getStreet()
	{
		return $this->street;
	}
	
	/**
	 * @return string
	 */
	public function getCity()
	{
		return $this->city;
	}
	
	/**
	 * @return string
	 */
	public function getCountry()
	{
		return $this->country;
	}

}