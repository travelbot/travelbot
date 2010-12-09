<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Entity for grouping events by search terms.
 * @author ondrej
 * @entity
 * @table(name="poigroup") 
 */
class PoiGroup extends SimpleEntity
{

    /**
     * @var float
     * @column(type="decimal")
     */
    private $latitude;

     /**
     * @var float
     * @column(type="decimal")
     */
    private $longitude;
	
	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @oneToMany(targetEntity="Poi", mappedBy="group", cascade={"persist"})	 
	 */
	private $pois;
	
	public function __construct()
	{
		$this->pois = new ArrayCollection;
	}
	
	public function getLatitude()
	{
		return $this->latitude;
	}
	
	public function setLatitude($latitude)
	{
		$this->latitude = (float) $latitude;
		return $this;
	}
	
	public function getLongitude()
	{
		return $this->longitude;
	}
	
	public function setLongitude($longitude)
	{
		$this->longitude = (float) $longitude;
		return $this;
	}
	
	public function getPois()
	{
		return $this->pois;
	}
	
	public function addPoi(Poi $poi)
	{
		if (!$this->pois->contains($poi)) {
			$this->pois->add($poi);
			$poi->group = $this;
		}
		
		return $this;
	}
	
	public function removePoi(Poi $poi)
	{
		if ($this->pois->contains($poi)) {
			$this->pois->removeElement($poi);
			$poi->group = NULL;
		}
		
		return $this;
	}

}
