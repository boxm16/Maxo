<?php

require_once 'Model/BusTrip.php';

class BreakController {
    /* important factors here
     * 1)if break is at a)A_B trip end, b)B_A or at c)any
     * 2)if more then 1 bus can stop  at the same time
     * 3)and if they can, can they stay on the same stop, or in case 1c on different stops
     * 4)break time frontlines, min and max time given for break time; existing now -[11-17]
     * 5)can break start at any place of a trip, i mean after any (including first )tripPeriod, or
     * there exists at list some amount of tripPeriods to be done to do your break
     */

    private $breakPoint;
    private $startFrontline;
    private $endFrontline;
    private $lastTripStartTime;
    private $abTripTimeMinutes;
    private $abTripTimeSeconds;
    private $baTripTimeMinutes;
    private $baTripTimeSeconds;
    private $breakTimeMinutes;
    private $breakTimeSeconds;
    private $allVersions;
    private $returnArray;
    private $distilledBreakTimeSequences;

    function __construct($lastTripStartTime, $abTripTimeMinutes, $abTripTimeSeconds, $baTripTimeMinutes, $baTripTimeSeconds, $breakTimeMinutes, $breakTimeSeconds) {
        $this->breakPoint = "ba"; //"ab", "ba" or "ab_ba" for both
        $this->startFrontline = "11:00:00";
        $this->endFrontline = "17:00:00";
        $this->allVersions = array();
        $this->lastTripStartTime = $lastTripStartTime;
        $this->abTripTimeMinutes = $abTripTimeMinutes;
        $this->abTripTimeSeconds = $abTripTimeSeconds;
        $this->baTripTimeMinutes = $baTripTimeMinutes;
        $this->baTripTimeSeconds = $baTripTimeSeconds;
        $this->breakTimeMinutes = $breakTimeMinutes;
        $this->breakTimeSeconds = $breakTimeSeconds;
    }

    public function getEveryBreakVariationForEveryRouteVariation($allRouteVersions) {
        $routeVersion = $allRouteVersions[0];
        $strippedRoute = $this->stripRoute($routeVersion);
        $this->returnArray = array();

        array_push($this->returnArray, $strippedRoute);
        $this->permuteRow(0, $strippedRoute, array());
        $breakTimeSequences = $this->getBreakTimeSequence();
        $this->distilledBreakTimeSequences = $this->purifyBreakSequences($breakTimeSequences);

        return $this->returnArray;
    }

    public function getDistilledBreakTimeSequences() {
        return $this->distilledBreakTimeSequences;
    }

    private function purifyBreakSequences($breakTimeSequences) {
        //this method(function) is for filtering out viable break sequences;
        $distilledBreakTimeSequences = array();

        foreach ($breakTimeSequences as $breakTimeSequence) {
            if ($this->breakTimeSequenceChecks($breakTimeSequence)) {
                array_push($distilledBreakTimeSequences, $breakTimeSequence);
            }
        }
        return $distilledBreakTimeSequences;
    }

    private function breakTimeSequenceChecks($breakTimeSequence) {
        $breakTimePool = array();
        foreach ($breakTimeSequence as $breakTimePeriod) {
            if ($this->breakTimePeriodExistsInBreakPool($breakTimePeriod, $breakTimePool)) {
                return false;
            } else {
                array_push($breakTimePool, $breakTimePeriod);
            }
        }return true;
    }

    private function breakTimePeriodExistsInBreakPool($breakTimePeriodStart, $breakTimePool) {
        if (count($breakTimePool) == 0) {
            return false;
        }
        foreach ($breakTimePool as $poolPeriodStartTime) {
            $differenceBetweenTwoStartTimesInSeconds = abs($poolPeriodStartTime - $breakTimePeriodStart);
            if ($differenceBetweenTwoStartTimesInSeconds < ($this->breakTimeMinutes * 60) + ($this->breakTimeSeconds * 1)) {
                return true;
            }
        }
        return false;
    }

    private function getBreakTimeSequence() {
        $breakSequenceArray = array();
        for ($x = 1; $x < count($this->returnArray); $x++) {
            $route = $this->returnArray[$x];
            $breakSequence = array();
            foreach ($route as $busTrip) {
                $tripPeriods = $busTrip->getTripPeriods();
                foreach ($tripPeriods as $tripPeriod) {
                    $a = $tripPeriod->getEndTimeInSeconds();
                    array_push($breakSequence, $a);
                }
            }

            array_push($breakSequenceArray, $breakSequence);
        }
        return $breakSequenceArray;
    }

    private function cloneRoute($route) {
        $clonedRoute = array();
        foreach ($route as $busTrip) {
            $tripPeriods = $busTrip->getTripPeriods();
            $clonedTripPeriods = $this->cloneTripPeriods($tripPeriods);
            $clonedBusTrip = clone $busTrip;
            $clonedBusTrip->setTripPeriods($clonedTripPeriods);
            array_push($clonedRoute, $clonedBusTrip);
        }
        return $clonedRoute;
    }

    private function cloneTripPeriods($tripPeriods) {
        $clonedTripPeriods = array();
        foreach ($tripPeriods as $tripPeriod) {
            $clonedTripPeriod = clone $tripPeriod;
            array_push($clonedTripPeriods, $clonedTripPeriod);
        }
        return $clonedTripPeriods;
    }

    private function permuteRow($RowNumber, $initialRoute, $collectorRoute) {
        //initialRoute is not ready(full) yet, it collects its busTrips
        // i need its copy here, not just cope but not dependant clone first


        $initialBusTrip = $initialRoute[$RowNumber];
        $initialTripPeriods = $initialBusTrip->getTripPeriods();

        for ($a = 0; $a < count($initialTripPeriods); $a++) {
            $route = $this->cloneRoute($collectorRoute);
            $newTripPeriods = array();
            $newSingleTripPeriod = $initialTripPeriods[$a];
            array_push($newTripPeriods, $newSingleTripPeriod);
            $newBusTrip = clone $initialBusTrip;
            $newBusTrip->setTripPeriods($newTripPeriods);
            array_push($route, $newBusTrip);
            if ($RowNumber == count($initialRoute) - 1) {
                array_push($this->returnArray, $route);
            }
            $nextRow = $RowNumber + 1;
            if ($nextRow < count($initialRoute)) {
                $this->permuteRow($nextRow, $initialRoute, $route);
            }
        }
    }

    private function stripRoute($route) {
        $strippedRoute = array();
        foreach ($route as $busTrip) {
            $tripPeriodsArray = $busTrip->getTripPeriods();
            $strippedTripPeriods = array();
            foreach ($tripPeriodsArray as $tripPeriod) {
                if ($this->tripPeriodChecks($tripPeriod)) {
                    array_push($strippedTripPeriods, $tripPeriod);
                }
            }
            $newBusTrip = clone $busTrip;
            $newBusTrip->setTripPeriods($strippedTripPeriods);
            array_push($strippedRoute, $newBusTrip);
        }
        return $strippedRoute;
    }

    private function tripPeriodChecks($tripPeriod) {
        if ($this->tripPeriodIsHalt($tripPeriod) || !$this->tripEndHasBreakPoint($tripPeriod) || $this->tripPeriodIsOutOfFrontlines($tripPeriod)) {
            return false;
        }return true;
    }

    private function tripPeriodIsHalt($tripPeriod) {
        $tripType = $tripPeriod->getType();
        return $tripType == "halt";
    }

    private function tripEndHasBreakPoint($tripPeriod) {
        if ($this->breakPoint == "ab_ba") {
            return true;
        } else {
            return $this->breakPoint == $tripPeriod->getType();
        }
        return true;
    }

    private function tripPeriodIsOutOfFrontlines($tripPeriod) {
        $breakStartFrontlineInSeconds = $this->getTimeInSecondsFromTimeStamp($this->startFrontline);
        $breakEndFrontlineInSeconds = $this->getTimeInSecondsFromTimeStamp($this->endFrontline) - $this->breakTimeMinutes * 60 - $this->breakTimeSeconds * 1;


        if ($tripPeriod->getEndTimeInSeconds() < $breakStartFrontlineInSeconds || $tripPeriod->getEndTimeInSeconds() > $breakEndFrontlineInSeconds) {
            return true;
        } else {
            return false;
        }
    }

// 

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

}
