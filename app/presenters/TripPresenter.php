<?php

use Nette\Environment;

/**
 * Presenter for listing and showing details of saved trips.
 * @author mirteond 
 */
class TripPresenter extends BasePresenter
{

	/** @persistent */
	public $id;
	
	/** @var Trip */
	private $trip;
	
	public function startup()
	{
		parent::startup();
		$service = new TripService($this->entityManager);
		$this->trip = $service->find($this->id);
	}
	
	protected function beforeRender()
	{
		parent::beforeRender();
		$this->template->trip = $this->trip;
	}

    public function actionBooking($flightId)
    {
        try {
        	//@todo refactor (add access to trip)
            $flightMapper = new FlightKayakMapper();
            $flightService = new FlightService($this->entityManager);
            $depart_date = new DateTime;
            $return_date = new DateTime('+1 week');
            $flights = $flightService->buildFlights($flightMapper, 'PRG', 'PAR', $depart_date, $return_date, '1', 'e', 'n');

            $this->redirectUri("http://kayak.com" . $flights[$flightId - 1]->getBook());
        } catch (FlightException $e)
        {
            $this->flashMessage('Redirect failed', 'error');
        }
    }

}
