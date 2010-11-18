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

		try {
			$eventService = new EventService;
			$config = Environment::getConfig('api');
			$events = $eventService->getEvents(
				$trip->arrival,
				new DateTime,
				new EventfulMapper($config->eventfulUser, $config->eventfulPassword, $config->eventfulKey)
			);
			$this->template->events = $events;
		} catch (InvalidStateException $e) {
			$this->template->events = array();
		}
		
        $articleService = new ArticleService($this->entityManager);
        $this->template->article = $articleService->buildArticle(
			$trip->arrival,
			new ArticleWikipediaMapper()
		);
		
         try {
            $flightMapper = new FlightKayakMapper();
            $flightService = new FlightService($this->entityManager);
            $this->template->flights = $flightService->buildFlights($flightMapper, 'PRG','PAR',"12/29/2010","12/30/2010",'1','e','n');
         } catch(FlightException $e)  {

         }
	}

}
