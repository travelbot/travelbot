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
	
	public function renderSummary()
	{
		$articleService = new ArticleService($this->entityManager);
        $this->template->article = $articleService->buildArticle($this->trip->arrival, new ArticleWikipediaMapper())->text;
	}

        public function actionBooking($flightId)
        {
                $departure = $this->trip->$departure;
		$arrival = $this->trip->$arrival;
            try {

         	$airportMapper = new AirportTravelMathMapper();
                $airportService = new AirportService();
                $locationService = new LocationService();
                $coordinates = $locationService->getCoordinates($departure);
                $from = $airportService->searchNearestAirport($airportMapper, $coordinates['latitude'], $coordinates['longitude']);
                $coordinates = $locationService->getCoordinates($arrival);
                $to = $airportService->searchNearestAirport($airportMapper, $coordinates['latitude'], $coordinates['longitude']);
                $flightMapper = new FlightKayakMapper();
                $flightService = new FlightService($this->entityManager);
                $depart_date = new DateTime('now');
                $return_date = new DateTime('+1 week');
                // @todo redo for JsonResponse
                $flights = $flightService->buildFlights($flightMapper, $from, $to, $depart_date, $return_date, '1', 'e', 'n');

                $this->redirectUri("http://kayak.com" . $flights[$flightId - 1]->getBook());
            } catch (FlightException $e)
            {
                $this->flashMessage('Redirect failed', 'error');
            }
        }

}
