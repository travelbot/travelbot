<?php

use Nette\Application\BadRequestException;
use Nette\Web\Uri;

class TripService extends EntityService
{

	/**
	 * Build a new Trip via Google Web Service Directions API.
	 * @param string
	 * @param string
	 * @return array	 	 	 
	 */
	public function buildTrip($departure, $arrival)
	{
		// curl initialization
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
		
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

		// json parsing
		$json = json_decode($result);
		if ($json == FALSE || $json->status != 'OK') {
			throw new InvalidStateException('Malformed JSON response.');
		}
		
		$rawSteps = $json->routes[0]->legs[0]->steps;
		$steps = array();
		foreach($rawSteps as $step) {
			$steps[] = new Step(
				$step->distance->value,
				$step->duration->value,
				htmlspecialchars_decode($step->html_instructions, ENT_QUOTES)
			);
		}
		return new Trip($departure, $arrival, $steps);
	}
	
	/**
	 * Persist trip to the database.
	 * @param Trip
	 * @return Trip	 	 
	 */
	public function save(Trip $trip)
	{
		$this->entityManager->persist($trip);
		$this->entityManager->flush();
		
		return $trip;
	}
	
	/**
	 * @param int
	 * @return Trip
	 * @throws Nette\Application\BadRequestException	 	 
	 */
	public function find($id)
	{
		$trip = $this->entityManager->find('Trip', (int) $id);
		if ($trip == NULL) {
			throw new BadRequestException('Trip not found.');
		}
		return $trip;
	}
	
	/**
	 * @return array
	 */
	public function findAll()
	{
		return $this->entityManager
			->createQuery('SELECT t FROM Trip t ORDER BY t.id ASC')
			->getResult();
	}

}
