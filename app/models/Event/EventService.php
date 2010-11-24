<?php

class EventService extends Service
{

	public function getEvents($location, DateTime $date, IEventMapper $mapper)
	{
		return $mapper->getEvents($location, $date);
	}

}
