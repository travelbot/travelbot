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
		
        $articleService = new ArticleService($this->entityManager);
        $this->template->article = $articleService->buildArticle(
			$trip->arrival,
			new ArticleWikipediaMapper()
		);
         try{
            $flightMapper = new FlightKayakMapper();
            $flightService = new FlightService($this->entityManager);
            $this->template->flights = $flightService->buildFlights($flightMapper, 'PRG','PAR',"11/12/2010","11/14/2010",'1','e','n');
         } catch(FlightException $e)  {

         }

	}

}
