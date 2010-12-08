<?php

require_once(__DIR__ . '/MockTripMapper.php');

class TripServiceTest extends TestCase
{

	public function testBuildTrips()
	{
		$service = new TripService($this->entityManager);
		$trip = $service->buildTrip('Praha', 'Brno', new MockTripMapper);
		
		$this->assertType('Trip', $trip);
		
		// data from MockTripMapper
		$this->assertEquals(3, $trip->distance);
		$this->assertEquals(6, $trip->duration);
		$this->assertEquals(3, count($trip->steps));
		
		// 3 cycles (3 steps)
		foreach($trip->steps as $step) {
			$this->assertType('Step', $step);
			$this->assertEquals('text', $step->instructions);
			$this->assertEquals(1, $step->distance);
			$this->assertEquals(2, $step->duration);
		}
	}

       //testsave and testfindall
        public function testSave(){
	        $service = new TripService($this->entityManager);
	        $trip1 = $service->buildTrip('Praha', 'Brno', new MockTripMapper);

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
                
                $trip1->addEvent($a);

	        $service->save($trip1);
	
	        $tripFound = $service->find($trip1->id);
	        $this->assertEquals($trip1, $tripFound);
        }
}
