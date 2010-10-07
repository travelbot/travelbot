<?php

use Nette\Application\Presenter;
use Nette\Environment;


/**
 * Base class for all application presenters.
 */
abstract class BasePresenter extends Presenter
{

	public function canonicalize()
	{
		// override canonization when running PHPUnit tests
		if (!Environment::isConsole()) {
			parent::canonicalize();
		}
	}
	
	public function getContext()
	{
		return $this->getApplication()->getContext();
	}
	
	public function getEntityManager()
	{
		return $this->getContext()->getService('Doctrine\ORM\EntityManager');
	}

}
