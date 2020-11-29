<?php

require_once 'TripPeriod.php';

class BusTrip {

    private $starterTrip;
    private $firstTripStartTime;
    private $lastTripStartTime;
    private $abTripTimeMinutes;
    private $abTripTimeSeconds;
    private $baTripTimeMinutes;
    private $baTripTimeSeconds;
    private $breakTimeMinutes;
    private $breakTimeSeconds;
    private $tripPeriods;

    function __construct($starterTrip, $firstTripStartTime, $lastTripStartTime, $abTripTimeMinutes, $abTripTimeSeconds, $baTripTimeMinutes, $baTripTimeSeconds, $breakTimeMinutes, $breakTimeSeconds) {
        $this->starterTrip = $starterTrip;
        $this->firstTripStartTime = $firstTripStartTime;
        $this->lastTripStartTime = $lastTripStartTime;
        $this->abTripTimeMinutes = $abTripTimeMinutes;
        $this->abTripTimeSeconds = $abTripTimeSeconds;
        $this->baTripTimeMinutes = $baTripTimeMinutes;
        $this->baTripTimeSeconds = $baTripTimeSeconds;
        $this->breakTimeMinutes = $breakTimeMinutes;
        $this->breakTimeSeconds = $breakTimeSeconds;
        $this->tripPeriods = array();
        $this->createTripPeriods();
    }

    public function getTripPeriods() {
        return $this->tripPeriods;
    }

    public function createTripPeriods() {

        $this->tripPeriods = array();


        $endTimeInSeconds = $this->getTimeInSecondsFromTimeStamp($this->lastTripStartTime);
        //here is the dispatcher that dispatches periods of trip
        //first halt period at start


        $haltLength = 5;       //halt time here
        $startPoint = $this->getPointFromTimeStamp($this->firstTripStartTime) - $haltLength;
        $startTimeInSeconds = $this->getTimeInSecondsFromTimeStamp($this->firstTripStartTime) - ($haltLength * 60);
        $length = $haltLength;
        $tripPeriod = new TripPeriod("halt", $startPoint, $startTimeInSeconds, $length);
        array_push($this->tripPeriods, $tripPeriod);
        //now getting ready for first Trip Period
        $startPoint = $this->getPointFromTimeStamp($this->firstTripStartTime);
        $startTimeInSeconds = $this->getTimeInSecondsFromTimeStamp($this->firstTripStartTime);

        $type = $this->starterTrip;
        while ($startTimeInSeconds < $endTimeInSeconds) {

            if ($type == "ab") {
                $length = $this->abTripTimeMinutes + $this->abTripTimeSeconds / 60;
                $tripPeriod = new TripPeriod($type, $startPoint, $startTimeInSeconds, $length);
                $startPoint += $length;
                $startTimeInSeconds += ($this->abTripTimeMinutes * 60) + ($this->abTripTimeSeconds * 1);
            } else {
                $length = $this->baTripTimeMinutes + $this->baTripTimeSeconds / 60;
                $tripPeriod = new TripPeriod($type, $startPoint, $startTimeInSeconds, $length);
                $startTimeInSeconds += ($this->baTripTimeMinutes * 60) + ($this->baTripTimeSeconds * 1);
                $startPoint += $length;
            }

            if ($type == "ab") {
                $type = "ba";
            } else {
                $type = "ab";
            }
            array_push($this->tripPeriods, $tripPeriod);
            //halt at the end of every trip(ciri)


            $length = $haltLength;
            $tripPeriod = new TripPeriod("halt", $startPoint, $startTimeInSeconds, $length);
            $startPoint += $haltLength;
            $startTimeInSeconds += $haltLength * 60;
            array_push($this->tripPeriods, $tripPeriod);
        }
    }

    private function getPointFromTimeStamp($timeStamp) {
        $splittedTime = explode(":", $timeStamp);
        $hours = $splittedTime[0];
        $minutes = $splittedTime[1];

        if (count($splittedTime) == 3) {
            $seconds = $splittedTime[2];
        } else {
            $seconds = 0;
        }
        return 30 + ($hours - 5) * 60 + ($minutes) + ($seconds / 60);
    }

    private function getTimeInSecondsFromTimeStamp($timeStamp) {
        $splittedTime = explode(":", $timeStamp);
        $hours = $splittedTime[0];
        $minutes = $splittedTime[1];
        if (count($splittedTime) == 3) {
            $seconds = $splittedTime[2];
        } else {
            $seconds = 0;
        }
        $totalSeconds = ($hours * 60 * 60) + ($minutes * 60) + ($seconds * 1);
        return $totalSeconds;
    }

    public function setTripPeriods($tripPeriods) {
        $this->tripPeriods = $tripPeriods;
    }

}
