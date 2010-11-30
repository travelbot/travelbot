<?php

class EventTest extends PHPUnit_Framework_TestCase
{

	public function testRegularEvent()
	{
		$a = new Event();
	}
	
	public function testTrips()
	{
		$a = new Event();

                $trip1 = new Trip('Praha', 'Brno');

                $a->addTrip($trip1);
		$this->assertEquals($trip1, $a->trips[0]);
                $trip1->addEvent($a);
		$this->assertEquals($a, $trip1->events[0]);
               
                $trip2 = new Trip('Praha', 'Dresden');

                $a->addTrip($trip2);
                $this->assertEquals($trip2, $a->trips[1]);
                $trip2->addEvent($a);
		$this->assertEquals($a, $trip2->events[0]);

		 //removing trip
		
               $a->removeTrip($a->trips[0]);
               $a->removeTrip($a->trips[1]);      
	}

}
