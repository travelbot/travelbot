<?php
/**
 * Description of AirportService
 *
 * @author Petr
 */
class AirportService {


    public function searchNearestAirport(IAirportMapper $mapper, $lat, $log)
    {
        $result = $mapper->searchNearestAirport($lat, $log);
        if (preg_match("([A-Z][A-Z][A-Z] / [A-Z][A-Z][A-Z][A-Z])", $result, $results) > 0)
        {
            if (preg_match("([A-Z][A-Z][A-Z])", $results[0], $results) > 0)
            {
                return $results[0];
            }
        }
        throw new AirportException("Location " . $lat . ", " . $log." could not be found.", null, null);
    }
}

