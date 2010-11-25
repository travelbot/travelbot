<?php

use Nette\Application\BadRequestException;

/**
 * Service for working with Trip objects.
 * @author mirteond
 */
class PoiService extends EntityService {



    public function getPois($lat, $lng, IPoiMapper $mapper) {
        return $mapper->getPois($lat, $lng);
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
