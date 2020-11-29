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

    function __construct($lastTripStartTime, $abTripTimeMinutes, $abTripTimeSeconds, $baTripTimeMinutes, $baTripTimeSeconds, $breakTimeMinutes, $breakTimeSeconds) {
        $this->breakPoint = "ab";
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

        $this->returnArray = array($routeVersion);
        $rewritenRoute = $this->rewriteRoute($routeVersion);
     //   $this->permuteRow(0, $rewritenRoute, array());

        return $this->returnArray;
    }

    function permuteRow($RowNumber, $routeVersion, $initialArray) {

        $arr = $routeVersion[$RowNumber];
        $tripPeriodsArray = $arr->getTripPeriods();
        for ($a = 0; $a < count($tripPeriodsArray); $a++) {
            $insideArray = $this->cloneArray($initialArray);
            array_push($insideArray, $tripPeriodsArray[$a]);

            if ($RowNumber == count($routeVersion) - 1) {
                $a = $b = $c = $d = $e = $f = $h = $t = $as = null;
                $busTrip = new BusTrip($a, $b, $c, $d, $e, $f, $h, $t, $as);
                $busTrip->setTripPeriods($insideArray);
                array_push($this->returnArray, $busTrip);
            }
            $nextRow = $RowNumber + 1;
            if ($nextRow < count($routeVersion)) {
                $this->permuteRow($nextRow, $routeVersion, $insideArray);
            }
        }
    }

    function cloneArray($array) {
        $nAr = array();

        foreach ($array as $item) {
            $copy = clone $item;
            array_push($nAr, $copy);
        }
        return $nAr;
    }

    function rewriteRoute($route) {
        return $route;
    }

}
