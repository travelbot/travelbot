<?php

use Nette\Application\BadRequestException;

/**
 * @author javi.cullera
 */

class Event_TripService extends EntityService
{
        /**
	 * Persist event to the database.
	 * @param Event_Trip
	 * @return Event_Trip
	 */
        public function save(Event_Trip $event_trip)
	{
            $this->entityManager->persist($event_trip);
            $this->entityManager->flush();

            return $event_trip;
	}

         /**
	 * Persist trip to the database.
	 * @param int
         * @param int
	 * @return Event_trip
	 */
	public function find($id1,$id2)
	{
            $event_trip = $this->entityManager->find('Event_Trip',  array ((int) $id1,(int) $id2));
            if ($event == NULL) {
		throw new BadRequestException('Event not found.');
            }
            return $event_trip;
	}

	/**
	 * @return array
	 */
	public function findAll()
	{
		return $this->entityManager
			->createQuery('SELECT e FROM event_trip e')
			->getResult();
	}
}
