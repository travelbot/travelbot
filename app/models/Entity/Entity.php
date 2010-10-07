<?php

/**
 * Base entity class.
 * @mappedSuperclass
 * @hasLifecycleCallbacks   
 */ 
abstract class Entity extends Nette\Object
{	


	/**
	 * Constraints check before entity persisting and updating.
	 * @prePersist
	 * @preUpdate	 	 	 
	 */	 	
	public function check()
	{
	
	}

}
