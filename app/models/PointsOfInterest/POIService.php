<?php


/**
 * Description of POIService
 *
 * @author Petr Vales
 */
class POIService extends EntityService {
    function getPOIs($lat, $lng, IPOIMapper $mapper) {
        $json = $mapper->getPointsOfInterest($lat, $lng);
        $pois = array();
        foreach ($jason as $poi) {
            $pois[] = new PointOfInterest($poi->result->name, $poi->result->vicinity,
                    $poi->result->types, $poi->result->phoneNumber, $poi->result->address,
                    $poi->geometry->location->lat, $poi->geometry->location->lng,
                    $poi->rating, $poi->url, $poi->icon, $poi->reference);
        }
        return $pois;
    }
}

