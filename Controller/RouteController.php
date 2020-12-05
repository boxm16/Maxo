<?php

include_once 'Model/BusTrip.php';
include_once 'Model/BusTripIgnitionCode.php';
include_once 'SequenceController.php';
include_once 'BreakCOntroller.php';

class RouteController {

    private $starterTrip;
    private $firstTripStartTime;
    private $lastTripStartTime;
    private $abTripTimeMinutes;
    private $abTripTimeSeconds;
    private $baTripTimeMinutes;
    private $baTripTimeSeconds;
    private $abBusCount;
    private $baBusCount;
    private $intervalTimeMinutes;
    private $intervalTimeSeconds;
    private $breakTimeMinutes;
    private $breakTimeSeconds;
    private $haltTimeMinutes;
    private $initialVersion;
    private $allVersions;
    private $allVersionsWithBreak;
    private $allSequences;

    function __construct($starterTrip, $firstTripStartTime, $lastTripStartTime, $abTripTimeMinutes, $abTripTimeSeconds, $baTripTimeMinutes, $baTripTimeSeconds, $abBusCount, $baBusCount, $intervalTimeMinutes, $intervalTimeSeconds, $breakTimeMinutes, $breakTimeSeconds) {
        $this->starterTrip = $starterTrip;
        $this->firstTripStartTime = $firstTripStartTime;
        $this->lastTripStartTime = $lastTripStartTime;
        $this->abTripTimeMinutes = $abTripTimeMinutes;
        $this->abTripTimeSeconds = $abTripTimeSeconds;
        $this->baTripTimeMinutes = $baTripTimeMinutes;
        $this->baTripTimeSeconds = $baTripTimeSeconds;
        $this->abBusCount = $abBusCount;
        $this->baBusCount = $baBusCount;
        $this->intervalTimeMinutes = $intervalTimeMinutes;
        $this->intervalTimeSeconds = $intervalTimeSeconds;
        $this->breakTimeMinutes = $breakTimeMinutes;
        $this->breakTimeSeconds = $breakTimeSeconds;
        $this->haltTimeMinutes = 5;
        $this->initialVersion = array();
        $this->allVersions = array();
        $this->allVersionsWithBreak = array();
    }

    public function getAllVersions() {
        $this->allSequences = $this->getAllSequences();
        foreach ($this->allSequences as $sequence) {
            $this->addRouteVersionToAllVersions($sequence);
        }
        return $this->allVersions;
    }

    public function getAllVersionsWithBreak() {

        $breakController = new BreakController($this->lastTripStartTime, $this->abTripTimeMinutes, $this->abTripTimeSeconds, $this->baTripTimeMinutes, $this->baTripTimeSeconds, $this->breakTimeMinutes, $this->breakTimeSeconds);
        $allVersionsWithBreak = $breakController->getEveryBreakVariationForEveryRouteVariation($this->allVersions);
        $distilledBreakTimeSequences = $breakController->getDistilledBreakTimeSequences();

        $sequenceForCreation = $this->allSequences[0];
        $arrayOfAllBreakVersionsForRoute = $this->createRouteVersionForFirstSequenceWithBreaks($sequenceForCreation, $distilledBreakTimeSequences);
        return $arrayOfAllBreakVersionsForRoute;
    }

    private function createRouteVersionForFirstSequenceWithBreaks($sequenceForCreation, $distilledBreakTimeSequences) {
        $breakVersionsForRoute = array();

        for ($a = 0; $a < count($distilledBreakTimeSequences); $a++) {
            $breakSequence = $distilledBreakTimeSequences[$a];

            $routeVersion = array();

            for ($x = 0; $x < count($sequenceForCreation); $x++) {
                $busTripIgnitionCode = $sequenceForCreation[$x];
                $starterTrip = $busTripIgnitionCode->getStarterTrip();
                $startTime = $busTripIgnitionCode->getStartTime();
                $busTrip = new BusTrip($starterTrip, $startTime, $this->lastTripStartTime, $this->abTripTimeMinutes, $this->abTripTimeSeconds, $this->baTripTimeMinutes, $this->baTripTimeSeconds, $this->breakTimeMinutes, $this->breakTimeSeconds);

                $busTrip->setBreakStartTime($breakSequence[$x]);
                $busTrip->recreateTrip();
                array_push($routeVersion, $busTrip);
            }



            array_push($breakVersionsForRoute, $routeVersion);
        }
        return $breakVersionsForRoute;
    }

    private function getAllSequences() {

        $initialSequence = $this->getInitialSequence();
        $allPossibleSequencesFromInitialSequence = $this->getAllPossibleSequencesFromInitialSequence($initialSequence);

        return $allPossibleSequencesFromInitialSequence;
    }

    private function getInitialSequence() {
        $initialSequence = array();
        $abBusCount = $this->abBusCount;
        $baBusCount = $this->baBusCount;
        $startTime = $this->firstTripStartTime;

        if ($this->starterTrip == "ab") {
            $goTrip = "ab";
            $returnTrip = "ba";
            $goBusCount = $this->abBusCount;
            $returnBusCount = $this->baBusCount;
        } else {
            $goTrip = "ba";
            $returnTrip = "ab";
            $goBusCount = $this->baBusCount;
            $returnBusCount = $this->abBusCount;
        }

        while ($goBusCount > 0) {
            $busTripIgnitionCode = new BusTripIgnitionCode($goTrip, $startTime);
            $startTime = $this->increaseStartTimeByInterval($startTime, $this->intervalTimeMinutes, $this->intervalTimeSeconds);
            array_push($initialSequence, $busTripIgnitionCode);
            $goBusCount--;
        }


        $startTime = $this->decreaseStartTimeByReturnTime($startTime, $returnTrip);
        while ($returnBusCount > 0) {
            $busTripIgnitionCode = new BusTripIgnitionCode($returnTrip, $startTime);
            $startTime = $this->increaseStartTimeByInterval($startTime, $this->intervalTimeMinutes, $this->intervalTimeSeconds);
            array_push($initialSequence, $busTripIgnitionCode);
            $returnBusCount--;
        }
        return $initialSequence;
    }

    private function addRouteVersionToAllVersions($sequence) {
        $routeVersion = array();

        foreach ($sequence as $busTripIgnitionCode) {
            $starterTrip = $busTripIgnitionCode->getStarterTrip();
            $startTime = $busTripIgnitionCode->getStartTime();
            $busTrip = new BusTrip($starterTrip, $startTime, $this->lastTripStartTime, $this->abTripTimeMinutes, $this->abTripTimeSeconds, $this->baTripTimeMinutes, $this->baTripTimeSeconds, $this->breakTimeMinutes, $this->breakTimeSeconds);
            array_push($routeVersion, $busTrip);
        }

        array_push($this->allVersions, $routeVersion);
    }

    private function decreaseStartTimeByReturnTime($startTime, $returnTrip) {
        $splittedTime = explode(":", $startTime);
        $hours = $splittedTime[0];
        $minutes = $splittedTime[1];
        if (count($splittedTime) == 3) {
            $seconds = $splittedTime[2];
        } else {
            $seconds = 0;
        }
        $seconds = ($hours * 60 * 60) + ($minutes * 60) + ($seconds * 1);

        if ($returnTrip == "ab") {
            $returnTripTimeSeconds = ($this->abTripTimeMinutes * 60) + ($this->abTripTimeSeconds * 1) + ($this->haltTimeMinutes * 60);
        } else {
            $returnTripTimeSeconds = ($this->baTripTimeMinutes * 60) + ($this->baTripTimeSeconds * 1) + ($this->haltTimeMinutes * 60);
        }

        $resultSeconds = $seconds - $returnTripTimeSeconds;
        $time = gmdate("H:i:s", $resultSeconds);

        return $time;
    }

    private function increaseStartTimeByInterval($startTime, $intervalTimeMinutes, $intervalTimeSeconds) {
        $splittedTime = explode(":", $startTime);
        $hours = $splittedTime[0];
        $minutes = $splittedTime[1];
        if (count($splittedTime) == 3) {
            $seconds = $splittedTime[2];
        } else {
            $seconds = 0;
        }


        $seconds = ($hours * 60 * 60) + ($minutes * 60) + ($seconds * 1);
        $intervalSeconds = ($intervalTimeMinutes * 60) + ($intervalTimeSeconds * 1);
        $resultSeconds = $seconds + $intervalSeconds;

        $time = gmdate("H:i:s", $resultSeconds);

        return $time;
    }

    private function getAllPossibleSequencesFromInitialSequence($initialSequence) {
        if ($this->starterTrip == "ab") {
            $goTrip = "ab";
            $returnTrip = "ba";
            $goBusCount = $this->abBusCount;
            $returnBusCount = $this->baBusCount;
            $goTripTimeInSeconds = ($this->abTripTimeMinutes * 60) + ($this->abTripTimeSeconds * 1);
            $returnTripTimeInSeconds = ($this->baTripTimeMinutes * 60) + ($this->baTripTimeSeconds * 1);
        } else {
            $goTrip = "ba";
            $returnTrip = "ab";
            $goBusCount = $this->baBusCount;
            $returnBusCount = $this->abBusCount;
            $goTripTimeInSeconds = ($this->baTripTimeMinutes * 60) + ($this->baTripTimeSeconds * 1);
            $returnTripTimeInSeconds = ($this->abTripTimeMinutes * 60) + ($this->abTripTimeSeconds * 1);
        }
        if ($goBusCount <= $returnBusCount) {
            $allPossibleSequences = array();
            array_push($allPossibleSequences, $initialSequence);
            return $allPossibleSequences;
        }
        $index = -1;
        $standartSequence = array();
        foreach ($initialSequence as $busTripIgnitionCode) {

            $index++;
            $one = $this->getTimeInSecondsFromTimeStamp($busTripIgnitionCode->getStartTime());
            $two = $this->haltTimeMinutes * 60;
            $three = $returnTripTimeInSeconds;
            $four = $this->getTimeInSecondsFromTimeStamp($this->firstTripStartTime);
            if ($one - $two - $three >= $four) {
                break;
            }
            array_push($standartSequence, $busTripIgnitionCode);
        }
        $nonStandartSequence = array();
        $pararellSequence = array();

        for ($x = $index; $x < count($initialSequence); $x++) {
            $ignitionCode = $initialSequence[$x];
            array_push($nonStandartSequence, $ignitionCode);
            if ($ignitionCode->getStarterTrip() == $goTrip) {
                array_push($pararellSequence, "o");
            } else {
                array_push($pararellSequence, "W");
            }
        }
        $sequenceController = new SequenceController();
        $allNonStandartSequences = $sequenceController->getAllPossibleSequencesOfNonStardartSequence($nonStandartSequence, $pararellSequence, $this->intervalTimeMinutes, $this->intervalTimeSeconds);

        $allPosibleSequences = $this->combineStandartAndAllNonStandartSequencese($standartSequence, $allNonStandartSequences);

        return $allPosibleSequences;
    }

    private function combineStandartAndAllNonStandartSequencese($standartSequence, $allNonStandartSequences) {
        $allPossibleSequences = array();
        foreach ($allNonStandartSequences as $nonStandartSequence) {
            $combinedSequence = array_merge($standartSequence, $nonStandartSequence);
            array_push($allPossibleSequences, $combinedSequence);
        }

        return $allPossibleSequences;
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

}
