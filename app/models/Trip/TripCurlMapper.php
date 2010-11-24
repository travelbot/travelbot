<?php

use Nette\Web\Uri;

/**
 * Mapper for getting trip directions via curl and Google Web Services Directions API.
 * @author mirteond 
 */
class TripCurlMapper extends Nette\Object implements ITripMapper
{

	public function getTripDirections($departure, $arrival)
	{
		// curl initialization
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
		curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);
		
		// using Nette\Web\Uri for escaping GET parameters
		$uri = new Uri('http://maps.googleapis.com/maps/api/directions/json');
		$uri->setQuery(array(
			'origin' => $departure,
			'destination' => $arrival,
			'sensor' => 'false',
		));
		
		curl_setopt($c, CURLOPT_URL, (string) $uri);
		$result = curl_exec($c);
		curl_close($c);
		
		$json = json_decode($result);
		if ($json == FALSE || $json->status != 'OK') {
			throw new InvalidStateException('Malformed JSON response.');
		}
		
		return $json;
	}

}
