<?php

use Nette\Application\PresenterRequest;

class AboutPresenterText extends TestCase
{

	public function testRenderDefault()
	{
		$presenter = new AboutPresenter;
		$request = new PresenterRequest('About', 'GET', array());
		$response = $presenter->run($request);
		$this->assertType('Nette\Application\RenderResponse', $response);
	}

}
