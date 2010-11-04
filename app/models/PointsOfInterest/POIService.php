<?php

/**
 * Description of POIService
 *
 * @author Petr Vales
 */
class POIService extends EntityService {

    public function getPOIs($lat, $lng, IPOIMapper $mapper) {
        $json = $mapper->getPointsOfInterest($lat, $lng);
        $pois = array();
        foreach ($json as $item) {
        	$poi = new PointOfInterest;
        	$poi->name = $item->result->name;
        	$poi->vincinity = $item->result->vincinity;
        	$poi->types = $item->result->types;
        	$poi->phoneNumber = $item->result->phoneNumber;
        	$poi->address = $item->result->address;
        	$poi->lat = $item->geometry->location->lat;
        	$poi->lng = $item->geometry->location->lng;
			$poi->rating = $item->rating;
			$poi->url = $item->url;
			$poi->icon = $item->icon;
			$poi->reference = $item->reference;
			$pois[] = $poi;
        }
        return $pois;
    }
}
