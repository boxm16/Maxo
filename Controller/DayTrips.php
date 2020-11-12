<?php

require_once 'Model/BusDayTrips.php';

class DayTrips {

    private $dayTrips = array();
    private $busTripNumber;
    private $firstTripType;
    private $firstTripStartTime;
    private $lastTripStartTime;
    private $aTripTime;
    private $bTripTime;
    private $intervalTime;
    private $break;

    public function getDayTrips() {
        $GoGo=7;
        $stop=23;
        $this->firstTripStartTime = ($GoGo-5)*60+30;
        $this->lastTripStartTime = ($stop-5)*60+30;
        $this->aTripTime = 55;
        $this->bTripTime = 55;
        $this->intervalTime = 15;
        $this->breakTime = 15;
        for ($x = 0; $x < 4; $x++) {
            $this->busTripNumber = $x;
            $this->firstTripType = "a";

            $busDayTrips = new BusDayTrips($this->busTripNumber, $this->firstTripType, $this->firstTripStartTime, $this->lastTripStartTime, $this->aTripTime,$this->bTripTime, $this->intervalTime, $this->breakTime);
            array_push($this->dayTrips, $busDayTrips->getBusDayTrips());
        }

        for ($x = 0; $x < 4; $x++) {
            $this->busTripNumber = $x;
            $this->firstTripType = "b";

            $busDayTrips = new BusDayTrips($this->busTripNumber, $this->firstTripType, $this->firstTripStartTime, $this->lastTripStartTime,  $this->bTripTime, $this->aTripTime, $this->intervalTime,$this->breakTime);
            array_push($this->dayTrips, $busDayTrips->getBusDayTrips());
        }


       
        return $this->dayTrips;
    }

}
