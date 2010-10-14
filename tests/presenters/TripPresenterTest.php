<?php

use Nette\Application\PresenterRequest;

class TripPresenterTest extends TestCase
{

	public function testRenderDefault()
	{
		$presenter = new TripPresenter;
		$request = new PresenterRequest('Trip', 'GET', array());
		$response = $presenter->run($request);
		$this->assertType('Nette\Application\RenderResponse', $response);
	}
	
	public function testRenderShow()
	{
		$trip = new Trip('Praha (test)', 'Brno (test)');
		$this->entityManager->persist($trip);
		$this->entityManager->flush();
		
		$presenter = new TripPresenter;
		$request = new PresenterRequest('Trip', 'GET', array('action' => 'show', 'id' => $trip->id));
		$response = $presenter->run($request);
		$this->assertType('Nette\Application\RenderResponse', $response);
	}
	
	/**
	 * @expectedException Nette\Application\BadRequestException
	 */
	public function testNotFound()
	{
		$presenter = new TripPresenter;
		$request = new PresenterRequest('Trip', 'GET', array('action' => 'show', 'id' => uniqid('foobar')));
		$response = $presenter->run($request);
	}

}
