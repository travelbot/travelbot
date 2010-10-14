<?php

use Nette\Application\BadRequestException;

class TripService extends EntityService
{

	/**
	 * Build a new Trip via Google Web Service Directions API.
	 * @param string
	 * @param string
	 * @return array	 	 	 
	 */
	public function buildTrip($departure, $arrival, ITripMapper $mapper)
	{
		$json = $mapper->getTripDirections($departure, $arrival);
		
		// json decoding
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
