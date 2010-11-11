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
    public function handlePois() {
        $location = $this->request->post['location'];
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

        $jsonResponse = array();
        foreach ($pois as $poi) {
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

		try {
			// fallback in case of failed loading
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
	}

}
