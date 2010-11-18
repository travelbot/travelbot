<?php

/**
 *
 * @author Petr
 */
interface IAirportMapper
{
    public function searchNearestAirport($lat, $log);
}

