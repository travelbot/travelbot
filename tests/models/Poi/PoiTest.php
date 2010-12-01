<?php

class PoiTest extends PHPUnit_Framework_TestCase
{

	public function testRegularEvent()
	{
		$a = new Poi();
	}
	
	public function testTrips()
	{
		$a = new Poi();

                $trip1 = new Trip('Praha', 'Brno');

                $a->addTrip($trip1);
		$this->assertEquals($trip1, $a->trips[0]);
                $trip1->addPoi($a);
		$this->assertEquals($a, $trip1->pois[0]);
               
                $trip2 = new Trip('Praha', 'Dresden');
                
                $a->addTrip($trip2);
		$this->assertEquals($trip2, $a->trips[1]);
                $trip2->addPoi($a);
		$this->assertEquals($a, $trip2->pois[0]);

		// removing trip
		
               $a->removeTrip($a->trips[0]);
               $a->removeTrip($a->trips[1]);
	}

}
