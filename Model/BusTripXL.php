<?php

class BusTripXL {

    private $number;
    private $bus;
    private $driver;
    private $reportNumber;
    private $tripPeriods;
    private $baseLeavingTimeScheduled;
    private $baseLeavingTimeActual;
    private $baseReturnTimeScheduled;
    private $baseReturnTimeActal;

    function __construct($number, $bus, $driver, $reportNumber, $baseLeavingTimeScheduled, $baseLeavingTimeActual, $baseReturnTimeScheduled, $baseReturnTimeActal) {
        $this->number = $number;
        $this->bus = $bus;
        $this->driver = $driver;
        $this->reportNumber = $reportNumber;
        $this->baseLeavingTimeScheduled = $baseLeavingTimeScheduled;
        $this->baseLeavingTimeActual = $baseLeavingTimeActual;
        $this->baseReturnTimeScheduled = $baseReturnTimeScheduled;
        $this->baseReturnTimeActal = $baseReturnTimeActal;
        $this->tripPeriods = array();
    }

    public function addTripPeriodToTripPeriodsArray($tripPeriod) {
        array_push($this->tripPeriods, $tripPeriod);
    }

    public function getTripPeriods() {
        return $this->tripPeriods;
    }
    function getNumber() {
        return $this->number;
    }



}
