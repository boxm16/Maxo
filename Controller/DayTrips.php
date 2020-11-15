<?php

require_once 'Model/BusDayTrips.php';

class DayTrips {

    private $dayTrips = array();
    private $busTripNumber;
    private $abBusTripCount;
    private $baBusTripCount;
    private $firstTripType;
    private $firstTripStartTime;
    private $lastTripStartTime;
    private $aTripTime;
    private $bTripTime;
    private $intervalTime;
    private $break;

    function __construct($firstTripStartTime, $lastTripStartTime, $aTripTime, $bTripTime, $abBusTripCount, $baBusTripCount, $intervalTime) {
        $this->firstTripStartTime = $firstTripStartTime;
        $this->lastTripStartTime = $lastTripStartTime;



        $this->aTripTime = $aTripTime;
        $this->bTripTime = $bTripTime;

        $this->abBusTripCount = $abBusTripCount;
        $this->baBusTripCount = $baBusTripCount;


        $this->intervalTime = $intervalTime;
    }

    public function getDayTrips() {

        $splittedFirstTripStartTime = explode(":", $this->firstTripStartTime);
        $firstTripStartHour = $splittedFirstTripStartTime[0];
        $firstTripStartMinute = $splittedFirstTripStartTime[1];

        $splittedLasttTripStartTime = explode(":", $this->lastTripStartTime);
        $lastTripStartHour = $splittedLasttTripStartTime[0];
        $lastTripStartMinute = $splittedLasttTripStartTime[1];

        $this->firstTripStartTime = ($firstTripStartHour - 5) * 60 + $firstTripStartMinute + 30;
        $this->lastTripStartTime = ($lastTripStartHour - 5) * 60 + $lastTripStartMinute + 30;

        $this->breakTime = 15;
        for ($x = 0; $x < $this->abBusTripCount; $x++) {
            $this->busTripNumber = $x;
            $this->firstTripType = "a";

            $busDayTrips = new BusDayTrips($this->busTripNumber, $this->firstTripType, $this->firstTripStartTime, $this->lastTripStartTime, $this->aTripTime, $this->bTripTime, $this->intervalTime, $this->breakTime);
            array_push($this->dayTrips, $busDayTrips->getBusDayTrips());
        }

        for ($x = 0; $x < $this->baBusTripCount; $x++) {
            $this->busTripNumber = $x;
            $this->firstTripType = "b";

            $busDayTrips = new BusDayTrips($this->busTripNumber, $this->firstTripType, $this->firstTripStartTime, $this->lastTripStartTime, $this->bTripTime, $this->aTripTime, $this->intervalTime, $this->breakTime);
            array_push($this->dayTrips, $busDayTrips->getBusDayTrips());
        }



        return $this->dayTrips;
    }

}
