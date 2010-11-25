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

		/*
         try {
         	$airportMapper = new AirportTravelMathMapper();
            $airportService = new AirportService();
            $locationService = new LocationService();
            $coordinates = $locationService->getCoordinates($trip->getDeparture());
            $from = $airportService->searchNearestAirport($airportMapper, $coordinates['latitude'], $coordinates['longitude']);
            $coordinates = $locationService->getCoordinates($trip->getArrival());
            $to = $airportService->searchNearestAirport($airportMapper, $coordinates['latitude'], $coordinates['longitude']);
         	
            $flightMapper = new FlightKayakMapper();
            $flightService = new FlightService($this->entityManager);
            $depart_date = new DateTime('now');
            $return_date = new DateTime('+1 week');
            $this->template->flights = $flightService->buildFlights($flightMapper, $from, $to, $depart_date, $return_date, '1', 'e', 'n');
        }
        catch (FlightException $e)
        {
            $this->template->flightsError = "Connection with search system <a href='http://kayak.com'>Kayak</a> failed.";
        }
        catch (AirportException $e)
        {
            $this->template->flightsError = $e->getMessage();
        }*/
        $this->template->flights = array();
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
