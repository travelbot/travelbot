<?php

use Nette\Web\Uri;

class DirectionsService extends Service
{

	/**
	 * Returns array with navigation directions.
	 * @param string
	 * @param string
	 * @return array	 	 	 
	 */
	public function getDirections($from, $to)
	{
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
		
		$uri = new Uri('http://maps.googleapis.com/maps/api/directions/json');
		$uri->setQuery(array(
			'origin' => $from,
			'destination' => $to,
			'sensor' => 'false',
		));
		
		curl_setopt($c, CURLOPT_URL, (string) $uri);
		$result = curl_exec($c);
		curl_close($c);
		
		$json = json_decode($result);
		if ($json->status == 'OK') {
			$legs = $json->routes[0]->legs[0];
		}
		
		return array(
			'status' => $json->status,
			'steps' => isset($legs) ? $legs->steps : array(),
			'duration' => isset($legs) ? $legs->duration->text : '',
			'distance' => isset($legs) ? $legs->distance->text : '',
		);
	}

}
