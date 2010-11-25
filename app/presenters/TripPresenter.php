<?php

use Nette\Environment;

/**
 * Presenter for listing and showing details of saved trips.
 * @author mirteond 
 */
class TripPresenter extends BasePresenter
{

	public function renderDefault()
	{
		$service = new TripService($this->entityManager);
		$this->template->trips = $service->findAll();
	}
	
	public function renderShow($id)
	{
		$service = new TripService($this->entityManager);
		$trip = $service->find($id);
		$this->template->trip = $trip;
    }

    public function actionBooking($flightId)
    {
        try
        {
        	//@todo refactor (add access to trip)
            $flightMapper = new FlightKayakMapper();
            $flightService = new FlightService($this->entityManager);
            $depart_date = DateTime::createFromFormat("m/d/Y", "12/29/2010");
            $return_date = DateTime::createFromFormat("m/d/Y", "12/30/2010");
            $flights = $flightService->buildFlights($flightMapper, 'PRG', 'PAR', $depart_date, $return_date, '1', 'e', 'n');

            $this->redirectUri("http://kayak.com" . $flights[$flightId - 1]->getBook());
        }
        catch (FlightException $e)
        {
            $this->flashMessage('Redirect failed', 'error');
        }
    }

}
