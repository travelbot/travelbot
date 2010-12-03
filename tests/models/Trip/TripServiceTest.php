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
        $trip2 = $service->buildTrip('Praha', 'Dresden', new MockTripMapper);

        $count=count($service->findAll());
        $service->save($trip1);
        $service->save($trip2);
        $this->assertEquals($count+2, count($service->findAll()));
        }

        public function testFind(){
        $service = new TripService($this->entityManager);
        $trip1=$service->find(1);
        $this->assertEquals($trip1->getId(),1);
        }
}
