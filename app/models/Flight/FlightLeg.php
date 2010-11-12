<?php

/**
 * Class representing flight trip's leg
 *
 * @author Petr
 */
class FlightLeg
{

    private $airline;
    private $airlineDisplay;
    private $orig;
    private $dest;
    private $depart;
    private $arrive;
    private $stops;
    private $durationMinutes;
    private $cabin;
    private $segments;

    public function __construct($airline, $airlineDisplay, $orig, $dest,
            $depart, $arrive, $stops, $durationMinutes, $cabin, $segments)
    {
        $this->airline = $airline;
        $this->airlineDisplay = $airlineDisplay;
        $this->orig = $orig;
        $this->dest = $dest;
        $this->depart = $depart;
        $this->arrive = $arrive;
        $this->stops = $stops;
        $this->durationMinutes = $durationMinutes;
        $this->cabin = $cabin;
        $this->segments = $segments;
    }

    public function getAirline()
    {
        return $this->airline;
    }

    public function getAirlineDisplay()
    {
        return $this->airlineDisplay;
    }

    public function getOrig()
    {
        return $this->orig;
    }

    public function getDest()
    {
        return $this->dest;
    }

    public function getDepart()
    {
        return $this->depart;
    }

    public function getArrive()
    {
        return $this->arrive;
    }

    public function getStops()
    {
        return $this->stops;
    }

    public function getDurationMinutes()
    {
        return $this->durationMinutes;
    }

    public function getDurationTime()
    {
        return floor($this->getDurationMinutes()/60) . "h " . $this->getDurationMinutes()%60 . "min";
    }

    public function getCabin()
    {
        return $this->cabin;
    }

    public function getSegments()
    {
        return $this->segments;
    }

    public function setAirline($airline)
    {
        $this->airline = $airline;
    }

    protected function setAirlineDisplay($airlineDisplay)
    {
        $this->airlineDisplay = $airlineDisplay;
    }

    protected function setOrig($orig)
    {
        $this->orig = $orig;
    }

    protected function setDest($dest)
    {
        $this->dest = $dest;
    }

    protected function setDepart($depart)
    {
        $this->depart = $depart;
    }

    protected function setArrive($arrive)
    {
        $this->arrive = $arrive;
    }

    protected function setStops($stops)
    {
        $this->stops = $stops;
    }

    protected function setDurationMinutes($durationMinutes)
    {
        $this->durationMinutes = $durationMinutes;
    }

    protected function setCabin($cabin)
    {
        $this->cabin = $cabin;
    }

    protected function setSegments($segments)
    {
        $this->segments = $segments;
    }

}
