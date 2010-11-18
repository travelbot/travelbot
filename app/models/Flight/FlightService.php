<?php

/**
 * Description of FlightService
 *
 * @author Petr
 * @version 11.11.2010
 */
class FlightService extends EntityService
{

    public function buildFlights(IFlightMapper $mapper, $from, $to, DateTime $depart_date,
            DateTime $return_date, $travelers, $cabin, $oneWay)
    {
        $result = $mapper->searchFlights($from, $to, $depart_date, $return_date, $travelers, $cabin, $oneWay);
        if(strpos($result, "<searchresult>") == false) {
            throw new FlightException("Searing error", null, null);
        }
        $xmlResult = new SimpleXMLElement($result);
        if (!$xmlResult)
        {
            throw new FlightException("Searing error", null, null);
        }
        $xmlTrips = $xmlResult->trips;
        return $this->parseTrips($xmlTrips);
    }

    private function parseTrips($xmlTrips)
    {
        $flightTrips = array();
        foreach ($xmlTrips->trip as $xmlTrip)
        {
            $price = (string) $xmlTrip->price;
            $att = $xmlTrip->price->attributes();
            $book = $att[0];
            $currency = $att[1];
            $xmlLegs = $xmlTrip->legs;
            $legs = $this->parseLegs($xmlLegs);
            $flightTrips[] = new FlightTrip($price, $currency, $book, $legs);
        }
        return $flightTrips;
    }

    private function parseLegs($xmlLegs)
    {
        $legs = array();
        foreach ($xmlLegs->leg as $xmlLeg)
        {
            $airline = (string) $xmlLeg->airline;
            $airlineDisplay = (string) $xmlLeg->airline_display;
            $orig = (string) $xmlLeg->orig;
            $dest = (string) $xmlLeg->dest;
            $depart = DateTime::createFromFormat("Y/m/d H:i", (string) $xmlLeg->depart);     //2006/05/06 17:28
            $arrive = DateTime::createFromFormat("Y/m/d H:i", (string) $xmlLeg->arrive);     //2006/05/06 17:28(string) $xmlLeg->arrive;
            $stops = (string) $xmlLeg->stop;
            $durationMinutes = (string) $xmlLeg->duration_minutes;
            $cabin = (string) $xmlLeg->cabin;
            $segments = $this->parseSegments($xmlLeg);
            $legs[] = new FlightLeg($airline, $airlineDisplay, $orig, $dest, $depart, $arrive, $stops, $durationMinutes, $cabin, $segments);
        }
        return $legs;
    }

    private function parseSegments($xmlLeg)
    {
        $segments = array();
        foreach ($xmlLeg->segment as $xmlSegment)
        {
            $airlineS = (string) $xmlSegment->airline;
            $flight = (string) $xmlSegment->flight;
            $durationMinutesS = (string) $xmlSegment->duration_minutes;
            $equip = (string) $xmlSegment->equip;
            $miles = (string) $xmlSegment->miles;
            $dt = (string) $xmlSegment->dt;
            $o = (string) $xmlSegment->o;
            $at = (string) $xmlSegment->at;
            $d = (string) $xmlSegment->d;
            $cabinS = (string) $xmlSegment->cabin;
            $segments[] = new FlightSegment($airlineS, $flight, $durationMinutesS, $equip, $miles, $dt, $o, $at, $d, $cabinS);
        }
        return $segments;
    }

}

