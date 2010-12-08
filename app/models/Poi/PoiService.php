<?php

use Nette\Application\BadRequestException;

/**
 * Service for working with Trip objects.
 * @author mirteond
 */
class PoiService extends EntityService {



    public function getPois($lat, $lng, IPoiMapper $mapper) {
    	try {
			return $this->entityManager->createQuery('SELECT e FROM PoiGroup e WHERE e.latitude = ?1 AND e.longitude = ?2')
				->setParameter('1', $lat)
				->setParameter('2', $lng)
				->setMaxResults(1)
				->getSingleResult()
				->getPois();
		} catch (Doctrine\ORM\NoResultException $e) {
			$pois = $mapper->getPois($lat, $lng);
			$group = new PoiGroup;
			$group->latitude = $lat;
			$group->longitude = $lng;
			
			foreach($pois as $poi) {
				$group->addPoi($poi);
			}
			
			$this->entityManager->persist($group);
			$this->entityManager->flush();
			
			return $pois;
		}
    }

    public function save(Poi $poi)
	{
		$this->entityManager->persist($poi);
		$this->entityManager->flush();

		return	 $poi;
	}
        
    public function find($id)
	{
		$poi= $this->entityManager->find('Poi', (int) $id);
		if ($poi == NULL) {
			throw new BadRequestException('Poi not found.');
		}
		return $poi;
	}

	/**
	 * @return array
	 */
    public function findAll()
	{
		return $this->entityManager
			->createQuery('SELECT p FROM Poi p ORDER BY p.id ASC')
			->getResult();
	}

}
