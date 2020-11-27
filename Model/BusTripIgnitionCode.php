<?php

class BusTripIgnitionCode {

    private $starterTrip;
    private $startTime;
    function __construct($starterTrip, $startTime) {
        $this->starterTrip = $starterTrip;
        $this->startTime = $startTime;
    }

    function getStarterTrip() {
        return $this->starterTrip;
    }

    function getStartTime() {
        return $this->startTime;
    }

    function setStarterTrip($starterTrip) {
        $this->starterTrip = $starterTrip;
    }

    function setStartTime($startTime) {
        $this->startTime = $startTime;
    }


}
