<?php

include_once 'Trip.php';

class BusDayTrips {

    private $busTripNumber;
    private $firstTripType;
    private $returnTripType;
    private $firstTripStartTime;
    private $lastTripStartTime;
    private $aTripTime;
    private $bTripTime;
    private $haltTime;
    private $intervalTime;
    private $breakTime;

    function __construct($busTripNumber, $firstTripType, $firstTripStartTime, $lastTripStartTime, $aTripTime, $bTripTime, $intervalTime, $breakTime) {
        $this->busTripNumber = $busTripNumber;
        $this->firstTripType = $firstTripType;
        if ($this->firstTripType == "a") {
            $this->returnTripType = "b";
        } else {
            $this->returnTripType = "a";
        }
        $this->firstTripStartTime = $firstTripStartTime;
        $this->lastTripStartTime = $lastTripStartTime;
        $this->aTripTime = $aTripTime;
        $this->bTripTime = $bTripTime;
        $this->intervalTime = $intervalTime;
        $this->breakTime = $breakTime;
        $this->haltTime = 5;
    }

    private function getRoundsCount() {

        $roundTime = ($this->haltTime * 2) + $this->aTripTime + $this->bTripTime;
        $timeLaps = $this->lastTripStartTime - $this->firstTripStartTime - ($this->intervalTime * $this->busTripNumber) + 1;
        return $timeLaps / $roundTime;
    }

    public function getBusDayTrips() {
        $busDayTripsArray = array();
        $rounds = $this->getRoundsCount();
        $startTime = $this->firstTripStartTime - $this->haltTime + ($this->busTripNumber * $this->intervalTime);
        $lastTripTime = $this->lastTripStartTime + ($this->busTripNumber * $this->intervalTime);

        $halt_1 = new Trip("halt", $startTime, $this->haltTime);
        array_push($busDayTripsArray, $halt_1);
        $startTime += $this->haltTime;
        $dispatcher = 0;
        
        while ($startTime <= $lastTripTime) {


            if ($dispatcher % 2 == 0) {
                $trip = new Trip($this->firstTripType, $startTime, $this->aTripTime);
                array_push($busDayTripsArray, $trip);
                $startTime += $this->aTripTime;
                
            } else {
                $trip = new Trip($this->returnTripType, $startTime, $this->bTripTime);
                array_push($busDayTripsArray, $trip);
                $startTime += $this->bTripTime;
            }
            $dispatcher++;
            $halt_2 = new Trip("halt", $startTime, $this->haltTime);
            array_push($busDayTripsArray, $halt_2);
            $startTime += $this->haltTime;
        }

        return $busDayTripsArray;
    }

}
