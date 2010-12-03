<?php

class PoiTest extends PHPUnit_Framework_TestCase
{

	public function testRegularEvent()
	{
		$a = new Poi();
                $a->setName('event1');
                $this->assertEquals('event1', $a->getName());
                
                $a->setAddress('address1');
                $this->assertEquals('address1', $a->getAddress());
                
                $a->setImageUrl('url1');
                $this->assertEquals('url1', $a->getImageUrl());
                
                $a->setLatitude('5');
                $this->assertEquals('5', $a->getLatitude());
                
                $a->setLongitude('5');
                $this->assertEquals('5', $a->getLongitude());
                
                $a->setTypes('types1');
                $this->assertEquals('types1', $a->getTypes());

                $a->setUrl('URL1');
                $this->assertEquals('URL1', $a->getUrl());
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
               $this->assertEquals(1, count($a->trips));

               $a->removeTrip($a->trips[1]);
               $this->assertEquals(0, count($a->trips));
	}

}
