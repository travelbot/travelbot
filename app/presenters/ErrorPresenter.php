<?php

use Nette\Debug,
	Nette\Application\BadRequestException;



/**
 * Error presenter.
 * @author Nette Framework 
 */
class ErrorPresenter extends BasePresenter
{

	/**
	 * @param  Exception
	 * @return void
	 */
	public function renderDefault($exception)
	{
		if ($this->isAjax()) { // AJAX request? Just note this error in payload.
			Debug::log($exception);
			$this->payload->error = TRUE;
			$this->terminate();

		} elseif ($exception instanceof BadRequestException) {
			$code = $exception->getCode();
			$this->setView(in_array($code, array(403, 404, 405, 410, 500)) ? $code : '4xx'); // load template 403.phtml or 404.phtml or ... 4xx.phtml

		} else {
			$this->setView('500'); // load template 500.phtml
			Debug::log($exception, Debug::ERROR); // and log exception
		}
	}

}
