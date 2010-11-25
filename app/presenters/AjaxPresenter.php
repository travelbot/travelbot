<?php

use Nette\Application\JsonResponse;
use Nette\Environment;

class AjaxPresenter extends BasePresenter
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

}
