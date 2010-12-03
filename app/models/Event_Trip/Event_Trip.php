<?php


/**
 * Class representing trip with origin, destination and steps.
 * @author javi.cullera
 *
 * @entity
 * @table(name="event_trip")
 */
class Event_Trip extends SimpleEntity
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
	private $id_event;

        public function __construct($id_trip,$id_event) {
		$this->id_trip= $id_trip;
                $this->id_event= $id_event;
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

	public function setId_event($id_event)
	{
		$this->id_event =$id_event;
		return $this;
	}

	public function getId_event()
	{
		return $this->id_event;
	}
}

?>
