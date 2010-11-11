<?php

/**
 * Description of FlightService
 *
 * @author Petr
 */
class FlightService extends EntityService {

    public function buildFlights(IFlightMapper $mapper, $from, $to, $depart_dat, $return_date, $travelers, $cabin, $oneWay) {
        $result = $mapper->searchFlights($from, $to, $depart_dat, $return_date, $travelers, $cabin, $oneWay);
        $xmlResult = new SimpleXMLElement($result);
        $xmlTrips = $xmlResult->xpath('//trips');
        $flightTrips = array();
        while (list(, $xmlTrip) = each($xmlTrips)) {
            $price = (string) $xmlTrip->price;
            $legs = array();
            $xmlLegs = $xmlTrip->xpath('//legs');
            while (list(, $xmlLeg) = each($xmlLegs)) {
                $airline = (string) $xmlLeg->airline;
                $airlineDisplay = (string) $xmlLeg->airline_display;
                $orig = (string) $xmlLeg->orig;
                $dest = (string) $xmlLeg->dest;
                $depart = (string) $xmlLeg->depart;
                $arrive = (string) $xmlLeg->arrive;
                $stops = (string) $xmlLeg->stop;
                $durationMinutes = (string) $xmlLeg->duration_minutes;
                $cabin = (string) $xmlLeg->cabin;
                $segments = array();
                $xmlSegments = $xmlLeg->xpath('//segment');
                while (list(, $xmlSegment) = each($xmlSegments)) {
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
                $legs[] = new FlightLeg($airline, $airlineDisplay, $orig, $dest, $depart, $arrive, $stops, $durationMinutes, $cabin, $segments);
            }
            $flightTrip[] = new FlightTrip($price, $legs);
        }
    }

}

