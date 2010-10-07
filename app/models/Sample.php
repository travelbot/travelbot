<?php

/**
 * Sample entity.
 *  
 * @entity
 * @hasLifecycleCallbacks  
 */
class Sample extends SimpleEntity
{
	
	/**
	 * @var string
	 * @column	 
	 */
	private $title;

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * @var string
	 * @return Sample Fluent interface	 
	 */
	public function setTitle($title)
	{
		$title = trim($title); // whitespaces not allowed
		$this->title = $title ? $title : NULL; // empty strings not allowed
		return $this; // fluent interface
	}
	
	/**
	 * Constraints check.	
	 * @prePersist
	 * @preUpdate	 
	 */
	public function check()
	{
		parent::check();
		if ($this->title == NULL) {
			throw new TestException('Title must be set.');
		}
	}
	
}
