<?php

class TripTest extends TestCase
{

	public function testRegularTrip()
	{
		$a = new Trip('Praha', 'Brno');
		$this->assertEquals('Praha', $a->departure);
		$this->assertEquals('Brno', $a->arrival);
	}
	
	public function testSteps()
	{
		$a = new Trip('Praha', 'Brno');
		$a->addStep(new Step(5, 2, 'turn right'));
		
		$this->assertEquals(1, count($a->steps));
		$this->assertEquals(5, $a->distance);
		$this->assertEquals(2, $a->duration);
		
		$a->addStep(new Step(60, 75, 'turn left'));
		
		$this->assertEquals(2, count($a->steps));
		$this->assertEquals(65, $a->distance);
		$this->assertEquals(77, $a->duration);
		
		// sequence orders
		$this->assertEquals(1, $a->steps[0]->sequenceOrder);
		$this->assertEquals(2, $a->steps[1]->sequenceOrder);
		
		// removing first step
		$a->removeStep($a->steps[0]);
		
		// distance and duration of the second step
		$this->assertEquals(1, count($a->steps));
		$this->assertEquals(60, $a->distance);
		$this->assertEquals(75, $a->duration);
	}

}
