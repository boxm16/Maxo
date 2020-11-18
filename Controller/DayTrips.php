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
    private $aTripTimeMinute;
    private $aTripTimeSecond;
    private $bTripTimeMinute;
    private $bTripTimeSecond;
    private $intervalTimeMinute;
    private $intervalTimeSecond;
    private $break;

    function __construct($firstTripStartTime, $lastTripStartTime, $aTripTimeMinute, $aTripTimeSecond, $bTripTimeMinute, $bTripTimeSecond, $abBusTripCount, $baBusTripCount, $intervalTimeMinute, $intervalTimeSecond) {
        $this->firstTripStartTime = $firstTripStartTime;
        $this->lastTripStartTime = $lastTripStartTime;



        $this->aTripTimeMinute = $aTripTimeMinute;
        $this->aTripTimeSecond = $aTripTimeSecond;
        $this->bTripTimeMinute = $bTripTimeMinute;
        $this->bTripTimeSecond = $bTripTimeSecond;


        $this->abBusTripCount = $abBusTripCount;
        $this->baBusTripCount = $baBusTripCount;


        $this->intervalTimeMinute = $intervalTimeMinute;
        $this->intervalTimeSecond = $intervalTimeSecond;
    }

    public function getDayTrips() {


        $this->breakTime = 15;



        for ($x = 0; $x < $this->abBusTripCount; $x++) {
            $this->busTripNumber = $x;
            $this->firstTripType = "a";

            $busDayTrips = new BusDayTrips($this->busTripNumber, $this->firstTripType, $this->firstTripStartTime, $this->lastTripStartTime, $this->aTripTimeMinute, $this->aTripTimeSecond, $this->bTripTimeMinute, $this->bTripTimeSecond, $this->intervalTimeMinute, $this->intervalTimeSecond, $this->breakTime);
            array_push($this->dayTrips, $busDayTrips->getBusDayTrips());
        }

        for ($x = 0; $x < $this->baBusTripCount; $x++) {
            $this->busTripNumber = $x;
            $this->firstTripType = "b";

            $busDayTrips = new BusDayTrips($this->busTripNumber, $this->firstTripType, $this->firstTripStartTime, $this->lastTripStartTime, $this->bTripTimeMinute, $this->bTripTimeSecond, $this->aTripTimeMinute, $this->aTripTimeSecond, $this->intervalTimeMinute, $this->intervalTimeSecond, $this->breakTime);
            array_push($this->dayTrips, $busDayTrips->getBusDayTrips());
        }



        return $this->dayTrips;
    }

}
