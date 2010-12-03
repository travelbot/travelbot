<?php

class Event_TripTest extends PHPUnit_Framework_TestCase
{

	public function testRegularEvent_Trip()
	{
		$a = new Event_Trip(1,1);
                $this->assertEquals(1, $a->getId_event());
                $this->assertEquals(1, $a->getId_trip());
	}

}
