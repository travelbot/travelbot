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
    public function handlePositions() {
        $location = $this->request->post['location'];
        $service = new LocationService;
        $coords = $service->getCoordinates($location);
        
        $service = new POIService($this->entityManager);

        try {
        	$config = Environment::getConfig('api');
            $POIs = $service->getPOIs($coords['latitude'], $coords['longitude'], new POICurlMapper($config->placesId, $config->placesKey));
        } catch (InvalidStateException $e) {
            $this->terminate(new JsonResponse(array(
				'status' => 'FAIL',
            )));
        }

        $jsonResponse = array();
        foreach ($POIs as $POI) {
            $jsonResponse[] = array(
                'name' => $POI->name,
                'vicinity' => $POI->vicinity,
                'types' => $POI->types,
                'phoneNumber' => $POI->phoneNumber,
                'address' => $POI->address,
                'lat' => $POI->lat,
                'lng' => $POI->lng,
                'rating' => $POI->rating,
                'url' => $POI->url,
                'icon' => $POI->icon,
                'reference' => $POI->reference
            );
        }

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
	}

}
