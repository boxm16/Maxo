<?php

require_once 'Model/BusDayTrips.php';

class DayTrips {

    private $dayTrips = array();
    private $dayTripsWithBreak = array();
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
    private $breakTime;
    private $breaksPool = array();
 

    function __construct($firstTripStartTime, $lastTripStartTime, $aTripTimeMinute, $aTripTimeSecond, $bTripTimeMinute, $bTripTimeSecond, $abBusTripCount, $baBusTripCount, $intervalTimeMinute, $intervalTimeSecond, $breakTime) {
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
        
         $this->breakTime =$breakTime;
    }

    public function getDayTripsWithBreak() {
        $this->dayTripsWithBreak = array();
        $this->breaksPool = array();

       


        if ($this->baBusTripCount > $this->abBusTripCount) {
            $this->pushTripsWithBreakStartingFromB($this->breaksPool);
            $lastTrip = $this->dayTrips[count($this->dayTrips) - 1];
            $lastLastTrip = $lastTrip[1];

            $timeInt = intval($lastLastTrip->getStartTimeInSeconds() +
                            ($this->intervalTimeMinute * 60) + $this->intervalTimeSecond) -
                    (($this->aTripTimeMinute + 5) * 60) - $this->aTripTimeSecond;
            $this->firstTripStartTime = gmdate("H:i:s", $timeInt);


            $this->pushTripsWithBreakStartingFromA($this->breaksPool);
        } else {

            $this->pushTripsWithBreakStartingFromA($this->breaksPool);
            $lastTrip = $this->dayTrips[count($this->dayTrips) - 1];
            $lastLastTrip = $lastTrip[1];

            $timeInt = intval($lastLastTrip->getStartTimeInSeconds() +
                            ($this->intervalTimeMinute * 60) + $this->intervalTimeSecond) -
                    (($this->bTripTimeMinute + 5) * 60) - $this->bTripTimeSecond;
            $this->firstTripStartTime = gmdate("H:i:s", $timeInt);


            $this->pushTripsWithBreakStartingFromB($this->breaksPool);
        }


        return $this->dayTripsWithBreak;
    }

    private function pushTripsWithBreakStartingFromA($breaksPool) {
        for ($x = 0; $x < $this->abBusTripCount; $x++) {
            $this->busTripNumber = $x;
            $this->firstTripType = "a";

            $busDayTrips = new BusDayTrips($this->busTripNumber, $this->firstTripType, $this->firstTripStartTime, $this->lastTripStartTime, $this->aTripTimeMinute, $this->aTripTimeSecond, $this->bTripTimeMinute, $this->bTripTimeSecond, $this->intervalTimeMinute, $this->intervalTimeSecond, $this->breakTime, $breaksPool);

            array_push($this->dayTripsWithBreak, $busDayTrips->getBusDayTripsWithBreaks());
            $this->breaksPool = $busDayTrips->getBreaksPoolBack();
            $breaksPool = $this->breaksPool;
        }
    }

    private function pushTripsWithBreakStartingFromB($breaksPool) {
        for ($x = 0; $x < $this->baBusTripCount; $x++) {
            $this->busTripNumber = $x;
            $this->firstTripType = "b";

            $busDayTrips = new BusDayTrips($this->busTripNumber, $this->firstTripType, $this->firstTripStartTime, $this->lastTripStartTime, $this->bTripTimeMinute, $this->bTripTimeSecond, $this->aTripTimeMinute, $this->aTripTimeSecond, $this->intervalTimeMinute, $this->intervalTimeSecond, $this->breakTime, $breaksPool);
            array_push($this->dayTripsWithBreak, $busDayTrips->getBusDayTripsWithBreaks());
            $this->breaksPool = $busDayTrips->getBreaksPoolBack();
            $breaksPool = $this->breaksPool;
        }
    }

    public function getBreaksPoolCount() {
        return count($this->breaksPool);
    }

    //-------------------------------------------------------------------------
    public function getDayTrips() {
        $this->dayTrips = array();

        


        if ($this->baBusTripCount > $this->abBusTripCount) {
            $this->pushTripsStartingFromB();
            $lastTrip = $this->dayTrips[count($this->dayTrips) - 1];
            $lastLastTrip = $lastTrip[1];

            $timeInt = intval($lastLastTrip->getStartTimeInSeconds() +
                            ($this->intervalTimeMinute * 60) + $this->intervalTimeSecond) -
                    (($this->aTripTimeMinute + 5) * 60) - $this->aTripTimeSecond;
            $this->firstTripStartTime = gmdate("H:i:s", $timeInt);


            $this->pushTripsStartingFromA();
        } else {

            $this->pushTripsStartingFromA();
            $lastTrip = $this->dayTrips[count($this->dayTrips) - 1];
            $lastLastTrip = $lastTrip[1];

            $timeInt = intval($lastLastTrip->getStartTimeInSeconds() +
                            ($this->intervalTimeMinute * 60) + $this->intervalTimeSecond) -
                    (($this->bTripTimeMinute + 5) * 60) - $this->bTripTimeSecond;
            $this->firstTripStartTime = gmdate("H:i:s", $timeInt);


            $this->pushTripsStartingFromB();
        }


        return $this->dayTrips;
    }

    private function pushTripsStartingFromA() {
       
        for ($x = 0; $x < $this->abBusTripCount; $x++) {
            $this->busTripNumber = $x;
            $this->firstTripType = "a";

            $busDayTrips = new BusDayTrips($this->busTripNumber, $this->firstTripType, $this->firstTripStartTime, $this->lastTripStartTime, $this->aTripTimeMinute, $this->aTripTimeSecond, $this->bTripTimeMinute, $this->bTripTimeSecond, $this->intervalTimeMinute, $this->intervalTimeSecond, $this->breakTime, $this->breaksPool);
            array_push($this->dayTrips, $busDayTrips->getBusDayTrips());
        }
    }

    private function pushTripsStartingFromB() {
      
        for ($x = 0; $x < $this->baBusTripCount; $x++) {
            $this->busTripNumber = $x;
            $this->firstTripType = "b";

            $busDayTrips = new BusDayTrips($this->busTripNumber, $this->firstTripType, $this->firstTripStartTime, $this->lastTripStartTime, $this->bTripTimeMinute, $this->bTripTimeSecond, $this->aTripTimeMinute, $this->aTripTimeSecond, $this->intervalTimeMinute, $this->intervalTimeSecond, $this->breakTime, $this->breaksPool);
            array_push($this->dayTrips, $busDayTrips->getBusDayTrips());
        }
    }

}
