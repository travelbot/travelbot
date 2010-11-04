<?php

use Nette\Web\Uri;

/**
 * Class for working with Location object.
 * @author mirteond 
 */
class LocationService extends Service
{

	/**
	 * Returns Location found by GPS coordinates.
	 * @param string
	 * @param string
	 * @return Location	 	 	 
	 */
	public function getLocation($latitude, $longitude)
	{
		// curl initialization
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
		curl_setopt($c, CURLOPT_URL, 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . $latitude . ',' . $longitude . '&sensor=false');
		$result = curl_exec($c);
		curl_close($c);

		$city = '';
		$country = '';
		$street = '';
		
		// parsing json results
		$json = json_decode($result);
		if ($json == FALSE || $json->status != 'OK') {
			throw new InvalidStateException('Malformed JSON data.');
		}

		foreach($json->results[0]->address_components as $comp) {
			if ($comp->types == array('locality', 'political')) {
				$city = $comp->short_name;
			}
			if ($comp->types == array('country', 'political')) {
				$country = $comp->long_name;
			}
			if ($comp->types == array('route')) {
				$street = $comp->long_name;
			}
		}
		
		return new Location($street, $city, $country);
	}
	
	/**
	 * Returns coordinates by given location string.
	 * @param string
	 * @return array	 	 
	 */
	public function getCoordinates($location)
	{
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
		
		$uri = new Uri('http://maps.googleapis.com/maps/api/geocode/json');
		$uri->setQuery(array(
			'address' => $location,
			'sensor' => 'false',
		));
		
		curl_setopt($c, CURLOPT_URL, (string) $uri);
		$result = curl_exec($c);
		curl_close($c);
		
		// parsing json results
		$json = json_decode($result);
		if ($json == FALSE || $json->status != 'OK') {
			throw new InvalidStateException('Malformed JSON data.');
		}
		
		return array(
			'latitude' => $json->results[0]->geometry->location->lat,
			'longitude' => $json->results[0]->geometry->location->lng
		);
	}

}
