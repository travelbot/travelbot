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
	
	/**
	 * @return Nette\Context
	 */
	public function getContext()
	{
		return $this->getApplication()->getContext();
	}
	
	/**
	 * @return Doctrine\ORM\EntityManager
	 */
	public function getEntityManager()
	{
		return $this->getContext()->getService('Doctrine\ORM\EntityManager');
	}
	
	/**
	 * @return Nette\Templates\ITemplate
	 */
	protected function createTemplate()
	{
		$template = parent::createTemplate();
		
		// duration template helper
		$template->registerHelper('duration', function($value) {
			$seconds = $value;
			$minutes = round($seconds / 60);
			$hours = round($minutes / 60);
			if ($minutes < 1) {
				return $seconds . ' seconds';
			}
			if ($hours < 1) {
				return $minutes . ' minutes';
			}
			return $hours . ' hours and ' . $minutes%60 . ' minutes';
		});
		
		$template->registerHelper('distance', function($value) {
			if (($value / 1000) > 1) {
				return round($value / 1000) . ' kilometers';
			}
			return $value . ' meters';
		});
		
		return $template;
	}

}
