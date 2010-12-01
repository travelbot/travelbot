<?php

use Nette\Application\BadRequestException;

/**
 * Service for working with Trip objects.
 * @author mirteond
 */

class EventService extends EntityService
{
        /**
	 * Build a new Trip via Google Web Service Directions API.
	 * @param string
	 * @param DateTime
	 * @return array
	 */
	public function getEvents($location, DateTime $date, IEventMapper $mapper)
	{
            return $mapper->getEvents($location, $date);
	}

        /**
	 * Persist event to the database.
	 * @param Event
	 * @return Event
	 */
        public function save(Event $event)
	{
            $this->entityManager->persist($event);
            $this->entityManager->flush();

            return $event;
	}

         /**
	 * Persist trip to the database.
	 * @param int
	 * @return Event
	 */
	public function find($id)
	{
            $event = $this->entityManager->find('Event', (int) $id);
            if ($event == NULL) {
		throw new BadRequestException('Event not found.');
            }
            return $event;
	}

	/**
	 * @return array
	 */
	public function findAll()
	{
		return $this->entityManager
			->createQuery('SELECT e FROM Event e ORDER BY e.id ASC')
			->getResult();
	}

}
