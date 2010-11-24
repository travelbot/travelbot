<?php

use Nette\Environment;
use Nette\ObjectMixin;

/**
 * Base test class.
 */
abstract class TestCase extends PHPUnit_Framework_TestCase
{

	/**
	 * @return Doctrine\ORM\EntityManager
	 */	 	
	public function getEntityManager()
	{
		return Environment::getApplication()->getContext()
			->getService('Doctrine\ORM\EntityManager');
	}
	
	
	/** Nette\Object functionality */
	
	/**
	 * Call to undefined method.
	 * @param  string  method name
	 * @param  array   arguments
	 * @return mixed
	 * @throws \MemberAccessException
	 */
	public function __call($name, $args)
	{
		return ObjectMixin::call($this, $name, $args);
	}



	/**
	 * Call to undefined static method.
	 * @param  string  method name (in lower case!)
	 * @param  array   arguments
	 * @return mixed
	 * @throws \MemberAccessException
	 */
	public static function __callStatic($name, $args)
	{
		$class = get_called_class();
		throw new \MemberAccessException("Call to undefined static method $class::$name().");
	}
	
	
	
	/**
	 * Returns property value. Do not call directly.
	 * @param  string  property name
	 * @return mixed   property value
	 * @throws \MemberAccessException if the property is not defined.
	 */
	public function &__get($name)
	{
		return ObjectMixin::get($this, $name);
	}



	/**
	 * Sets value of a property. Do not call directly.
	 * @param  string  property name
	 * @param  mixed   property value
	 * @return void
	 * @throws \MemberAccessException if the property is not defined or is read-only
	 */
	public function __set($name, $value)
	{
		return ObjectMixin::set($this, $name, $value);
	}



	/**
	 * Is property defined?
	 * @param  string  property name
	 * @return bool
	 */
	public function __isset($name)
	{
		return ObjectMixin::has($this, $name);
	}



	/**
	 * Access to undeclared property.
	 * @param  string  property name
	 * @return void
	 * @throws \MemberAccessException
	 */
	public function __unset($name)
	{
		throw new \MemberAccessException("Cannot unset the property {$this->reflection->name}::\$$name.");
	}
	

}
