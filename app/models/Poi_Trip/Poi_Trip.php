<?php


/**
 * Class representing trip with origin, destination and steps.
 * @author javi.cullera
 *
 * @entity
 * @table(name="poi_trip")
 */
class Poi_Trip extends SimpleEntity
{
        /**
	 * @var integer
	 * @column
	 */
	private $id_trip;
	/**
	 * @var integer
	 * @column
	 */
	private $id_poi;

        public function __construct($id_trip,$id_poi) {
		$this->id_trip= $id_trip;
                $this->id_poi= $id_poi;
	}

	public function getId_trip()
	{
		return $this->id_trip;
	}

        public function setId_trip($id_trip)
	{
		$this->id_trip =$id_trip;
		return $this;
	}

	public function setId_poi($id_poi)
	{
		$this->id_poi =$id_poi;
		return $this;
	}

	public function getId_poi()
	{
		return $this->id_poi;
	}
}

?>
