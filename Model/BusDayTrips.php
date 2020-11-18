<?php

include_once 'Trip.php';

class BusDayTrips {

    private $busTripNumber;
    private $firstTripType;
    private $returnTripType;
    private $firstTripStartTime;
    private $lastTripStartTime;
    private $aTripTimeMinute;
    private $aTripTimeSecond;
    private $bTripTimeMinute;
    private $bTripTimeSecond;
    private $haltTime;
    private $intervalTimeMinute;
    private $intervalTimeSecond;
    private $breakTime;

    function __construct($busTripNumber, $firstTripType, $firstTripStartTime, $lastTripStartTime, $aTripTimeMinute, $aTripTimeSecond, $bTripTimeMinute, $bTripTimeSecond, $intervalTimeMinute, $intervalTimeSecond, $breakTime) {
        $this->busTripNumber = $busTripNumber;
        $this->firstTripType = $firstTripType;
        if ($this->firstTripType == "a") {
            $this->returnTripType = "b";
        } else {
            $this->returnTripType = "a";
        }
        $this->firstTripStartTime = $firstTripStartTime;
        $this->lastTripStartTime = $lastTripStartTime;
        $this->aTripTimeMinute = $aTripTimeMinute;
        $this->aTripTimeSecond = $aTripTimeSecond;
        $this->bTripTimeMinute = $bTripTimeMinute;
        $this->bTripTimeSecond = $bTripTimeSecond;
        $this->intervalTimeMinute = $intervalTimeMinute;
        $this->intervalTimeSecond = $intervalTimeSecond;
        $this->breakTime = $breakTime;
        $this->haltTime = 5;
    }

    private function timeToSeconds($time) {
        $timeExploded = explode(':', $time);
        if (isset($timeExploded[2])) {

            return $timeExploded[0] * 3600 + $timeExploded[1] * 60 + $timeExploded[2];
        }

        return $timeExploded[0] * 3600 + $timeExploded[1] * 60;
    }

    public function getBusDayTrips() {
        $busDayTripsArray = array();
        $splittedFirstTripStartTime = explode(":", $this->firstTripStartTime);
        $firstTripStartHour = $splittedFirstTripStartTime[0];
        $firstTripStartMinute = $splittedFirstTripStartTime[1];


        $splittedLasttTripStartTime = explode(":", $this->lastTripStartTime);
        $lastTripStartHour = $splittedLasttTripStartTime[0];
        $lastTripStartMinute = $splittedLasttTripStartTime[1];

        $firstTirpStartTimeInSeconds = $this->timeToSeconds($this->firstTripStartTime);
        $lastTirpStartTimeInSeconds = $this->timeToSeconds($this->lastTripStartTime);


        $this->firstTripStartTime = ($firstTripStartHour - 5) * 60 + $firstTripStartMinute + 30;
        $this->lastTripStartTime = ($lastTripStartHour - 5) * 60 + $lastTripStartMinute + 30;


        $startTime = $this->firstTripStartTime - $this->haltTime + ($this->busTripNumber * ($this->intervalTimeMinute + $this->intervalTimeSecond / 60));
        $startTimeInSeconds = $firstTirpStartTimeInSeconds - ($this->haltTime * 60) + ($this->busTripNumber * ($this->intervalTimeMinute * 60 + $this->intervalTimeSecond));

        $halt_1 = new Trip("halt", $startTime, $startTimeInSeconds, $this->haltTime);
        array_push($busDayTripsArray, $halt_1);
        $startTime += $this->haltTime;
        $startTimeInSeconds += $this->haltTime * 60;

        $dispatcher = 0;

        while ($startTimeInSeconds <= $lastTirpStartTimeInSeconds) {


            if ($dispatcher % 2 == 0) {
                $trip = new Trip($this->firstTripType, $startTime, $startTimeInSeconds, $this->aTripTimeMinute);
                array_push($busDayTripsArray, $trip);
                $startTime += $this->aTripTimeMinute;
                $startTimeInSeconds += (($this->aTripTimeMinute * 60) + $this->aTripTimeSecond);
            } else {
                $trip = new Trip($this->returnTripType, $startTime, $startTimeInSeconds, $this->bTripTimeMinute);
                array_push($busDayTripsArray, $trip);
                $startTime += $this->bTripTimeMinute;
                $startTimeInSeconds += (($this->bTripTimeMinute * 60) + $this->bTripTimeSecond);
            }
            $dispatcher++;
            $halt_2 = new Trip("halt", $startTime, $startTimeInSeconds, $this->haltTime);
            array_push($busDayTripsArray, $halt_2);
            $startTime += $this->haltTime;
            $startTimeInSeconds += $this->haltTime * 60;
        }

        return $busDayTripsArray;
    }

}
