<?php

/**
 * Description of AirportTravelMathMapper
 *
 * @author Petr
 */
class AirportTravelMathMapper implements IAirportMapper
{

    public function searchNearestAirport($lat, $log)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, "http://www.travelmath.com/closest-airport/" . $lat . "," . $log);
        $result = curl_exec($c);
        curl_close($c);
        return $result;
    }

}

