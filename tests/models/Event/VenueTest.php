<?php

class VenueTest extends TestCase
{

	public function testRegularVenue()
	{
		$a = new Venue('venue1', 'url1');
		$this->assertEquals('venue1', $a->name);
		$this->assertEquals('url1', $a->url);
	}

	public function testEvents()
	{
		$a = new Venue('venue1', 'url1');

                $event1=new Event();
		$a->addEvent($event1);
                $this->assertEquals($event1, $a->events[0]);

                $event2=new Event();
		$a->addEvent($event2);
                $this->assertEquals($event2, $a->events[1]);

		// removing  events
		$a->removeEvent($a->events[0]);
                 $this->assertEquals(1, count($a->events));
                $a->removeEvent($a->events[1]);
                 $this->assertEquals(0, count($a->events));
	}

}