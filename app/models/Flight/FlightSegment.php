<?php

/**
 * Class representing flight leg's segment
 *
 * @author Petr
 */
class FlightSegment {
    private  $airline;
    private  $flight;
    private  $durationMinutes;
    private  $equip;
    private  $miles;
    private  $dt;
    private  $o;
    private  $at;
    private  $d;
    private  $cabin;

    public function  __construct($airline, $flight, $durationMinutes, $equip, $miles, $dt, $o, $at, $d, $cabin) {
        $this->airline = $airline;
        $this->flight = $flight;
        $this->durationMinutes = $durationMinutes;
        $this->equip = $equip;
        $this->miles = $miles;
        $this->dt = $dt;
        $this->o = $o;
        $this->at = $at;
        $this->d = $d;
        $this->cabin = $cabin;
    }

    public function getAirline() {
        return $this->airline;
    }

    public function getFlight() {
        return $this->flight;
    }

    public function getDurationMinutes() {
        return $this->durationMinutes;
    }

    public function getEquip() {
        return $this->equip;
    }

    public function getMiles() {
        return $this->miles;
    }

    public function getDt() {
        return $this->dt;
    }

    public function getO() {
        return $this->o;
    }

    public function getAt() {
        return $this->at;
    }

    public function getD() {
        return $this->d;
    }

    public function getCabin() {
        return $this->cabin;
    }

    private function setAirline($airline) {
        $this->airline = $airline;
    }

    private function setFlight($flight) {
        $this->flight = $flight;
    }

    private function setDurationMinutes($durationMinutes) {
        $this->durationMinutes = $durationMinutes;
    }

    private function setEquip($equip) {
        $this->equip = $equip;
    }

    private function setMiles($miles) {
        $this->miles = $miles;
    }

    private function setDt($dt) {
        $this->dt = $dt;
    }

    private function setO($o) {
        $this->o = $o;
    }

    private function setAt($at) {
        $this->at = $at;
    }

    private function setD($d) {
        $this->d = $d;
    }

    private function setCabin($cabin) {
        $this->cabin = $cabin;
    }
}

