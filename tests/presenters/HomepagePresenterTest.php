<?php

use Nette\Application\PresenterRequest;

class HomepagePresenterTest extends TestCase
{

	public function testRenderDefault()
	{
		$presenter = new HomepagePresenter;
		$request = new PresenterRequest('Homepage', 'GET', array());
		$response = $presenter->run($request);
		$this->assertType('Nette\Application\RenderResponse', $response);
	}

}
