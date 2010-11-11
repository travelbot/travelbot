<?php

/**
 * Description of POIService
 *
 * @author Petr Vales
 */
class PoiService extends EntityService {

    public function getPois($lat, $lng, IPoiMapper $mapper) {
        return $mapper->getPois($lat, $lng);
    }
}
