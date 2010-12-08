<?php

use Doctrine\DBAL\Types\Type;
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
		try {
			$before = clone $date;
			$before->modify('-1 day');
			
			$after = clone $date;
			$after->modify('+1 day');
			
			$group = $this->entityManager->createQuery('SELECT e FROM EventGroup e WHERE e.location = ?1 AND e.date BETWEEN ?2 AND ?3')
				->setParameter('1', $location)
				->setParameter('2', $before, Type::DATETIMETZ)
				->setParameter('3', $after, Type::DATETIMETZ)
				->setMaxResults(1)
				->getSingleResult();
			
			return $group->events;
        } catch (Doctrine\ORM\NoResultException $e) {
			$events = $mapper->getEvents($location, $date);
	      	$group = new EventGroup;
	      	$group->location = $location;
	      	$group->date = $date;
	      	
	      	foreach($events as $event) {
				$group->addEvent($event);
			}
			
			$this->entityManager->persist($group);
			$this->entityManager->flush();
			
			return $events;
		}
        
        return $group->events;
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
