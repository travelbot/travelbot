<?php

use Doctrine\ORM\EntityManager;

/**
 * Base service for entity operations. It contains injected Doctrine EntityManager.
 * @author mirteond  
 */ 
abstract class EntityService extends Service
{

	/**
	 * @var Doctrine\ORM\EntityManager
	 */	 	
	private $entityManager;
	
	/**
	 * @param Doctrine\ORM\EntityManager
	 */	 	
	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}
	
	/**
	 * @return Doctrine\ORM\EntityManager
	 */	 	
	public function getEntityManager()
	{
		return $this->entityManager;
	}

}
