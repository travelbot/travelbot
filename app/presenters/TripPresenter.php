<?php

use Nette\Application\JsonResponse;
use Nette\Environment;

/**
 * Presenter for listing and showing details of saved trips.
 * @author mirteond 
 */
class TripPresenter extends BasePresenter
{

    /**
     * @author Petr Vales
     * @version 4.11.2010
     */
    public function handlePois()
    {
        $location = $this->request->post['location'];
        $service = new LocationService;
        $coords = $service->getCoordinates($location);

        $service = new PoiService($this->entityManager);

        try
        {
            $key = Environment::getConfig('api')->gowallaKey;
            $pois = $service->getPois($coords['latitude'], $coords['longitude'], new PoiGowallaMapper($key));
        }
        catch (InvalidStateException $e)
        {
            $this->terminate(new JsonResponse(array(
                        'status' => 'FAIL',
                    )));
        }

        $jsonResponse = array();
        foreach ($pois as $poi)
        {
            $jsonResponse['pois'][] = array(
                'name' => $poi->name,
                'types' => $poi->types,
                'address' => $poi->address,
                'latitude' => $poi->latitude,
                'longitude' => $poi->longitude,
                'url' => $poi->url,
                'icon' => $poi->imageUrl,
            );
        }

        $jsonResponse['status'] = 'OK';
        $jsonResponse['latitude'] = $coords['latitude'];
        $jsonResponse['longitude'] = $coords['longitude'];
        $this->terminate(new JsonResponse($jsonResponse));
    }

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

        try
        {
            // fallback in case of failed loading
            $eventService = new EventService;
            $config = Environment::getConfig('api');
            $events = $eventService->getEvents(
                            $trip->arrival,
                            new DateTime,
                            new EventfulMapper($config->eventfulUser, $config->eventfulPassword, $config->eventfulKey)
            );
            $this->template->events = $events;
        }
        catch (InvalidStateException $e)
        {
            $this->template->events = array();
        }

        $articleService = new ArticleService($this->entityManager);
        $this->template->article = $articleService->buildArticle(
                        $trip->arrival,
                        new ArticleWikipediaMapper()
        );
        try
        {
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
        }
    }

    public function renderBooking($flightId)
    {
        try
        {
            $flightMapper = new FlightKayakMapper();
            $flightService = new FlightService($this->entityManager);
            $depart_date = DateTime::createFromFormat("m/d/Y", "12/29/2010");
            $return_date = DateTime::createFromFormat("m/d/Y", "12/30/2010");
            $flights = $flightService->buildFlights($flightMapper, 'PRG', 'PAR', $depart_date, $return_date, '1', 'e', 'n');

            $this->redirectUri("http://kayak.com" . $flights[$flightId - 1]->getBook());
        }
        catch (FlightException $e)
        {
            $this->template->flightsError = "Connection with booking system failed.";
        }
    }

}
