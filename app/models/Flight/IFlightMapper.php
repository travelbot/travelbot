<?php

/**
 * Interface for getting flights between given airports
 *
 * @author Petr
 */
interface IFlightMapper
{
    public function searchFlights($from, $to, $depart_dat, $return_date,
            $travelers, $cabin, $oneWay);
}

