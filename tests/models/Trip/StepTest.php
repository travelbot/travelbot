<?php

class StepTest extends TestCase
{

	public function testRegularStep()
	{
		$a = new Step(5, 15, 'turn left');
		$this->assertEquals(5, $a->distance);
		$this->assertEquals(15, $a->duration);
		$this->assertEquals('turn left', $a->instructions);
	}
	
	public function testTrip()
	{
		$a = new Step(5, 10, 'turn right');
		$a->trip = $trip = new Trip('Praha', 'Brno');
		
		$this->assertEquals($trip, $a->trip);
                $this->assertEquals($a, $trip->steps[0]);
                $this->assertEquals(1, count($trip->steps));
                
	}
	
	public function testSequenceOrder()
	{
		$a = new Step(5, 10, 'go along');
		$this->assertEquals(0, $a->sequenceOrder); // default
		
		$a->sequenceOrder = 15;
		$this->assertEquals(15, $a->sequenceOrder);
	}

}
