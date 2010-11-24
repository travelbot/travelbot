<?php

/**
 * Interface for getting flights between given airports
 *
 * @author Petr
 */
interface IFlightMapper
{
    public function searchFlights($from, $to, DateTime $depart_date, DateTime $return_date,
            $travelers, $cabin, $oneWay);
}

