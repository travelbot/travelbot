<?php

use Nette\Application\BadRequestException;

/**
 * @author javi.cullera
 */

class Poi_TripService extends EntityService
{
        /**
	 * @param Poi_Trip
	 * @return Poi_Trip
	 */
        public function save(Poi_Trip $poi_trip)
	{
            $this->entityManager->persist($poi_trip);
            $this->entityManager->flush();

            return $poi_trip;
	}

         /**
	 * Persist trip to the database.
	 * @param int
         * @param int
	 * @return Poi_trip
	 */
	public function find($id1,$id2)
	{
            $poi_trip = $this->entityManager->find('Poi_Trip',  array ((int) $id1,(int) $id2));
            if ($poi == NULL) {
		throw new BadRequestException('poi not found.');
            }
            return $poi_trip;
	}

	/**
	 * @return array
	 */
	public function findAll()
	{
		return $this->entityManager
			->createQuery('SELECT et FROM poi_trip et')
			->getResult();
	}
}
