<?php

class EventTest extends PHPUnit_Framework_TestCase
{

	public function testRegularEvent()
	{
		$a = new Event();
                $a->setTitle('titulo1');
                $this->assertEquals('titulo1', $a->getTitle());
                
                $date1=new DateTime();
                $date1->setDate('2010','11','21');
                $a->setDate($date1);
                $this->assertEquals($date1,$a->getDate());

                $a->setDescription('descripcion1');
                $this->assertEquals('descripcion1', $a->getDescription());

                $a->setLatitude('5');
                $this->assertEquals('5', $a->getLatitude());

                $a->setLongitude('5');
                $this->assertEquals('5', $a->getLongitude());
                
                $a->setUrl('url1');
                $this->assertEquals('url1', $a->getUrl());
               
                $venue = new Venue('name1','url1');
                $a->setVenue($venue);
                $this->assertEquals($venue, $a->getVenue());
          
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
                $this->assertEquals(1, count($a->trips));
               $a->removeTrip($a->trips[1]);
                $this->assertEquals(0, count($a->trips));
	}

}
