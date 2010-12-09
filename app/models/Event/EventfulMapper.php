<?php

use Nette\Web\Uri;

class EventfulMapper extends Nette\Object implements IEventMapper
{

	private $user;
	
	private $password;
	
	private $key;
	
	public function __construct($user, $password, $key)
	{
		$this->user = $user;
		$this->password = $password;
		$this->key = $key;
	}
	
	public function getUser()
	{
		return $this->user;
	}
	
	public function getPassword()
	{
		return $this->password;
	}
	
	public function getKey()
	{
		return $this->key;
	}
	

	public function getEvents($location, DateTime $date)
	{
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
		//curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);
		
		$to = clone $date;
		$to->modify('+1 month');
		
		// using Nette\Web\Uri for escaping GET parameters
		$uri = new Uri('http://api.eventful.com/rest/events/search');
		$uri->setQuery(array(
			'user' => $this->user,
			'password' => $this->password,
			'app_key' => $this->key,
			'location' => $location,
			'within' => '10',
			'units' => 'km',
			'date' => $date->format('Ymd') . '00-' . $to->format('Ymd') . '00',
			//'sort_order' => 'date',
			//'sort_direction' => 'ascending',
		));
		
		//dump((string) $uri); die;
		
		curl_setopt($c, CURLOPT_URL, (string) $uri);
		$result = curl_exec($c);
		curl_close($c);
		
		$xml = @simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
		if ($xml == FALSE) {
			throw new InvalidStateException('Malformed XML response.');
		}
		
		$events = array();
		foreach($xml->events->event as $xmlEvent) {
			$event = new Event;
			$event->setTitle((string) $xmlEvent->title)
				->setUrl((string) $xmlEvent->url)
				->setDescription((string) $xmlEvent->description)
				->setDate(DateTime::createFromFormat('Y-m-d H:i:s', (string) $xmlEvent->start_time))
				->setVenue(new Venue((string) $xmlEvent->venue_name, (string) $xmlEvent->venue_url))
				->setLatitude((string) $xmlEvent->latitude)
				->setLongitude((string) $xmlEvent->longitude);
			$events[] = $event;
		}
		
		return $events;
	}

}
