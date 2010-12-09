<?php

use Nette\Application\AppForm;
use Nette\Application\JsonResponse;
use Nette\Environment;

class AjaxPresenter extends BasePresenter
{

	protected function createComponentEventsForm()
	{
		$form = new AppForm;
		//$form->addCheckbox('event');
		$form->elementPrototype->class('ajax');

		$form->addSubmit('okSubmit', 'Save preferences');
		$form->onSubmit[] = array($this, 'submitEventForm');
		
		return $form;
	}
	
	public function submitEventForm(AppForm $form)
	{
		$data = $form->httpData;
		
		$tripService = new TripService($this->entityManager);
		$trip = $tripService->find($data['tripId']);
		
		$eventService = new EventService($this->entityManager);
		
		foreach($trip->events as $event) {
			$trip->removeEvent($event);
		}
		
		$this->entityManager->flush();
		
		foreach($data as $key => $value) {
			if (substr($key, 0, 5) == 'event') {
				$event = $eventService->find(substr($key, 5));
				$trip->addEvent($event);
			}
		}
		
		$tripService->save($trip);
		
		$this->terminate();
	}
	
	public function handleEvents()
	{
		$location = $this->request->post['location'];
		$tripId = $this->request->post['tripId'];
		
		$tripService = new TripService($this->entityManager);
		$trip = $tripService->find($tripId);
		
		try {
			$eventService = new EventService($this->entityManager);
			$config = Environment::getConfig('api');
			$events = $eventService->getEvents(
				$location,
				$trip->departureDate != NULL ? $trip->departureDate : new DateTime,
				new EventfulMapper($config->eventfulUser, $config->eventfulPassword, $config->eventfulKey)
			);
		} catch (InvalidStateException $e) {
			$events = array();
		}
		
		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/../templates/Ajax/events.phtml');
		$template->events = $events;
		$template->trip = $trip;
		$template->form = $this['eventsForm'];
		
		$this->terminate(new JsonResponse(array('events' => (string) $template)));
	}
	
	protected function createComponentPoisForm()
	{
		$form = new AppForm;
		//$form->addCheckbox('event');
		$form->elementPrototype->class('ajax');

		$form->addSubmit('okSubmit', 'Save preferences');
		$form->onSubmit[] = array($this, 'submitPoiForm');
		
		return $form;
	}
	
	public function submitPoiForm(AppForm $form)
	{
		$data = $form->httpData;
		
		$tripService = new TripService($this->entityManager);
		$trip = $tripService->find($data['tripId']);
		
		$poiService = new PoiService($this->entityManager);
		
		foreach($trip->pois as $poi) {
			$trip->removePoi($poi);
		}
		
		$this->entityManager->flush();
		
		foreach($data as $key => $value) {
			if (substr($key, 0, 3) == 'poi') {
				$poi = $poiService->find(substr($key, 3));
				$trip->addPoi($poi);
			}
		}
		
		$tripService->save($trip);
		
		$this->terminate();
	}

	/**
     * @author Petr Vales
     * @version 4.11.2010
     */
    public function handlePois() {
        $location = $this->request->post['location'];
        $tripId = $this->request->post['tripId'];
        
        $service = new LocationService;
        $coords = $service->getCoordinates($location);
        
        $service = new PoiService($this->entityManager);

        try {
        	$key = Environment::getConfig('api')->gowallaKey;
            $pois = $service->getPois($coords['latitude'], $coords['longitude'], new PoiGowallaMapper($key));
        } catch (InvalidStateException $e) {
            $this->terminate(new JsonResponse(array(
				'status' => 'FAIL',
            )));
        }
        
        $tripService = new TripService($this->entityManager);
        
        $template = $this->createTemplate();
		$template->setFile(__DIR__ . '/../templates/Ajax/pois.phtml');
		$template->pois = $pois;
		$template->trip = $tripService->find($tripId);
		$template->form = $this['poisForm'];
		
		$this->terminate(new JsonResponse(array(
			'pois' => (string) $template,
			'latitude' => $coords['latitude'],
			'longitude' => $coords['longitude'],
		)));
    }

	/**
     * AJAX signal handler for getting user location string.
     */
    public function handleLocation() {
        $latitude = $this->request->post['latitude'];
        $longitude = $this->request->post['longitude'];
        $service = new LocationService;

        try {
            $location = $service->getLocation($latitude, $longitude);
			$this->terminate(new JsonResponse(array(
				'status' => 'OK',
				'location' => $location->street . ', ' . $location->city . ', ' . $location->country,
			)));
        } catch (InvalidStateException $e) {
            $this->terminate(new JsonResponse(array(
            	'status' => 'FAIL',
			)));
        }
    }

    /**
     * AJAX signal handler for getting navigation directions info.
     */
    public function handleDirections() {
        $from = $this->request->post['from'];
        $to = $this->request->post['to'];

        $service = new TripService($this->entityManager);

        try {
            $trip = $service->buildTrip($from, $to, new TripCurlMapper);
        } catch (InvalidStateException $e) {
            $this->terminate(new JsonResponse(array('status' => 'FAIL')));
        }

        $steps = array();
        foreach ($trip->steps as $step) {
            $arr = array();
            $arr['distance'] = $step->distance;
            $arr['instructions'] = $step->instructions;
            $arr['polyline'] = $step->getPolyline();
            $steps[] = $arr;
        }

        $this->terminate(new JsonResponse(array(
			'status' => 'OK',
			'duration' => $trip->duration,
			'distance' => $trip->distance,
			'steps' => $steps,
        )));
    }
    
    // AJAX handlers for asynchronous API requests
    
    public function handleArticle()
	{
		$location = $this->request->post['location'];
		
		$articleService = new ArticleService($this->entityManager);
        $this->terminate(new JsonResponse(array(
			'article' => $articleService->buildArticle(
				$location,
				new ArticleWikipediaMapper()
			)->text,
		)));
	}
	
	public function handleFlights()
	{
		$departure = $this->request->post['departure'];
		$arrival = $this->request->post['arrival'];
		$tripId = $this->request->post['tripId'];
		
		try {
         	$airportMapper = new AirportTravelMathMapper();
            $airportService = new AirportService();
            $locationService = new LocationService();
            $coordinates = $locationService->getCoordinates($departure);
            $from = $airportService->searchNearestAirport($airportMapper, $coordinates['latitude'], $coordinates['longitude']);
            $coordinates = $locationService->getCoordinates($arrival);
            $to = $airportService->searchNearestAirport($airportMapper, $coordinates['latitude'], $coordinates['longitude']);
         	
            $flightMapper = new FlightKayakMapper();
//            $flightMapper = new FlightMockMapper();
            $flightService = new FlightService($this->entityManager);
            
            $tripService = new TripService($this->entityManager);
            $trip = $tripService->find($tripId);
            
            $depart_date = $trip->departureDate != NULL ? $trip->departureDate : new DateTime;
            $return_date = $trip->arrivalDate != NULL ? $trip->arrivalDate : new DateTime('+1 week');
            // @todo redo for JsonResponse
            $flights = $flightService->buildFlights($flightMapper, $from, $to, $depart_date, $return_date, '1', 'e', 'n');
        }
        catch (FlightException $e)
        {
            $flights = array();
        }
        catch (AirportException $e)
        {
            $flights = array();
        }
        
        $template = $this->createTemplate();
        $template->setFile(__DIR__ . '/../templates/Ajax/flights.phtml');
        $template->flights = $flights;
        
        $this->terminate(new JsonResponse(array('flights' => (string) $template)));
	}

        public function handleHotels()
        {
            	$destination = $this->request->post['arrival'];
                $locationService = new LocationService();
                $coordinates = $locationService->getCoordinates($destination);
                $startdate = new DateTime('now');
                $enddate = new DateTime('+1 week');
		try {
			$hotelService = new HotelService;
			$config = Environment::getConfig('api');
                        $hotelMapper = new HotwireHotelMapper($config->hotwireKey);
			$hotels = $hotelService->getHotels($hotelMapper,
                                                            $coordinates['latitude'],
                                                            $coordinates['longitude'],
                                                            $startdate,
                                                            $enddate,
                                                            1,  //rooms
                                                            2,  //adults
                                                            0); //children
                               
		} catch (BadRequestException $e) {
			$hotels = array();
		}
		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/../templates/Ajax/hotels.phtml');
		$template->hotels = $hotels;

		$this->terminate(new JsonResponse(array('hotels' => (string) $template)));
        }

}
