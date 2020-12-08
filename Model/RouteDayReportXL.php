<?php

class RouteDayReportXL {

    private $number;
    private $date;
    private $baseNumber;
    private $busTrips;
    private $notes;

    function __construct($number, $date, $baseNumber) {
        $this->number = $number;
        $this->date = $date;
        $this->baseNumber = $baseNumber;
        $this->busTrips = array();
    }

    public function setBusTrips($busTrips) {
        $this->busTrips = $busTrips;
    }

    public function addBusTripToBusTripsArray($busTrip) {
        array_push($this->busTrips, $busTrip);
    }

    public function getBusTrips() {
        return $this->busTrips;
    }

}
