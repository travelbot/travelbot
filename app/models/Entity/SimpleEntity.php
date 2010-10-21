<?php

/**
 * Base entity class with primary key (id).
 * @author mirteond 
 *  
 * @mappedSuperclass
 */
abstract class SimpleEntity extends Entity
{

	/**
	 * @id @column(type="integer")
	 * @generatedValue(strategy="AUTO")
	 * @var integer
	 */
	private $id;

	/**
	 * Returns entity primary key.
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

}
