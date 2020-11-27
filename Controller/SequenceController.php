<?php

require_once 'Model/BusTripIgnitionCode.php';

class SequenceController {

    public function getAllPossibleSequencesOfNonStardartSequence($nonStandartSequence, $parallelSequence, $intervalTimeMinutes, $intervalTimeSeconds) {
        $intervalTimeInSeconds = ($intervalTimeMinutes * 60) + ($intervalTimeSeconds * 1);
        $backwardSequences = $this->getBackwordSequences($nonStandartSequence, $parallelSequence, $intervalTimeInSeconds);
        $forwardSequences = $this->getForwardSequences($nonStandartSequence, $parallelSequence, $intervalTimeInSeconds);
        $allUniqueSequences = $this->sortOutSequences($backwardSequences, $forwardSequences);
        return $allUniqueSequences;
    }

    private function sortOutSequences($backwardSequences, $forwardSequences) {

        $allUniqueSequences = array();

        foreach ($backwardSequences as $sequence) {

            if (!$this->sequenceExistsInList($sequence, $allUniqueSequences)) {
                array_push($allUniqueSequences, $sequence);
            }
        }
        foreach ($forwardSequences as $sequence) {

            if (!$this->sequenceExistsInList($sequence, $allUniqueSequences)) {
                array_push($allUniqueSequences, $sequence);
            }
        }

        return $allUniqueSequences;
    }

    private function sequenceExistsInList($sequence, $allUniqueSequences) {

        if (count($allUniqueSequences) == 0) {
            return false;
        }
        foreach ($allUniqueSequences as $listSequence) {

            if ($this->twoSequencesEquals($sequence, $listSequence)) {

                return true;
            }
        }

        return false;
    }

    private function twoSequencesEquals($sequence, $listSequence) {
        $x = 0;
        while ($x < count($sequence)) {

            $ignitionCode1 = $sequence[$x];
            $ignitionCode2 = $listSequence[$x];

            if (!$this->twoIgnitionCodesEqual($ignitionCode1, $ignitionCode2)) {
                return false;
            }
            $x++;
        }return true;
    }

    private function twoIgnitionCodesEqual($ignitionCode1, $ignitionCode2) {

        $startTime1 = $ignitionCode1->getStartTime();
        $startTime2 = $ignitionCode2->getStartTime();
        $starterType1 = $ignitionCode1->getStarterTrip();
        $starterType2 = $ignitionCode2->getStarterTrip();
        if ($startTime1 == $startTime2 && $starterType1 == $starterType2) {
            return true;
        } else {
            return false;
        }
    }

    private function cloneArray($array) {

        $cloneArray = array();
        foreach ($array as $item) {
            array_push($cloneArray, $item);
        }
        return $cloneArray;
    }

    private function getForwardSequences($nonStandartSequence, $parallelSequence, $intervalTimeInSeconds) {
        $result = array();
        $resultParallel = array();
        $cloneNonStandartSequence = $this->cloneIgnitionArray($nonStandartSequence);
        $cloneParallelSequence = $this->cloneArray($parallelSequence);
        $x = 0;
        while ($x < count($cloneParallelSequence)) {
            if ($cloneParallelSequence[$x] != "o") {
                if ($this->previousIsNull($cloneParallelSequence, $x)) {
                    $temp = $cloneParallelSequence[$x];


                    $ignitionCode = $cloneNonStandartSequence[$x];
                    $startTime = $ignitionCode->getStartTime();
                    $startTimeInSeconds = $this->getTimeInSecondsFromTimeStamp($startTime);
                    $newStartTimeInSeconds = $startTimeInSeconds - $intervalTimeInSeconds;
                    $newTimeForIgnitionCode = gmdate("H:i:s", $newStartTimeInSeconds);
                    $ignitionCode->setStartTime($newTimeForIgnitionCode);


                    $cloneParallelSequence[$x] = "o";

                    $ignitionCode1 = $cloneNonStandartSequence[$x - 1];
                    $startTime1 = $ignitionCode1->getStartTime();
                    $startTimeInSeconds1 = $this->getTimeInSecondsFromTimeStamp($startTime1);
                    $newStartTimeInSeconds1 = $startTimeInSeconds1 + $intervalTimeInSeconds;
                    $newTimeForIgnitionCode1 = gmdate("H:i:s", $newStartTimeInSeconds1);
                    $ignitionCode1->setStartTime($newTimeForIgnitionCode1);

                    $cloneNonStandartSequence[$x] = $ignitionCode1;
                    $cloneParallelSequence[$x - 1] = $temp;
                    $cloneNonStandartSequence[$x - 1] = $ignitionCode;


                    array_push($result, $cloneNonStandartSequence);
                    array_push($resultParallel, $cloneParallelSequence);
                    $cloneParallelSequence = $this->cloneArray($cloneParallelSequence);
                    $cloneNonStandartSequence = $this->cloneIgnitionArray($cloneNonStandartSequence);

                    $x = 0;
                } else {
                    $x++;
                }
            } else {
                $x++;
            }
        }
        return $result;
    }

    private function previousIsNull($cloneParallelSequence, $x) {
        if ($x - 1 < 0) {
            return false;
        } else {
            return $cloneParallelSequence[$x - 1] == "o";
        }
    }

    private function getBackwordSequences($nonStandartSequence, $parallelSequence, $intervalTimeInSeconds) {
        $result = array();
        $resultParallel = array();
        array_push($result, $nonStandartSequence);
        array_push($resultParallel, $parallelSequence);

        $cloneNonStandartSequence = $this->cloneIgnitionArray($nonStandartSequence);
        $cloneParallelSequence = $this->cloneArray($parallelSequence);
        $x = count($cloneNonStandartSequence) - 1;

        while ($x != -1) {

            if ($cloneParallelSequence[$x] != "o") {
                $x--;
            } else {
                if ($this->nextIsNumber($cloneParallelSequence, $x)) {
                    $temp = $cloneParallelSequence[$x + 1];
                    $ignitionCode = $cloneNonStandartSequence[$x + 1];
                    $startTime = $ignitionCode->getStartTime();
                    $startTimeInSeconds = $this->getTimeInSecondsFromTimeStamp($startTime);
                    $newStartTimeInSeconds = $startTimeInSeconds - $intervalTimeInSeconds;
                    $newTimeForIgnitionCode = gmdate("H:i:s", $newStartTimeInSeconds);
                    $ignitionCode->setStartTime($newTimeForIgnitionCode);

                    $cloneParallelSequence[$x + 1] = "o";

                    $ignitionCode1 = $cloneNonStandartSequence[$x];
                    $startTime1 = $ignitionCode1->getStartTime();
                    $startTimeInSeconds1 = $this->getTimeInSecondsFromTimeStamp($startTime1);
                    $newStartTimeInSeconds1 = $startTimeInSeconds1 + $intervalTimeInSeconds;
                    $newTimeForIgnitionCode1 = gmdate("H:i:s", $newStartTimeInSeconds1);
                    $ignitionCode1->setStartTime($newTimeForIgnitionCode1);


                    $cloneNonStandartSequence[$x + 1] = $ignitionCode1;
                    $cloneParallelSequence[$x] = $temp;
                    $cloneNonStandartSequence[$x] = $ignitionCode;

                    array_push($result, $cloneNonStandartSequence);
                    array_push($resultParallel, $cloneParallelSequence);
                    $cloneParallelSequence = $this->cloneArray($cloneParallelSequence);
                    $cloneNonStandartSequence = $this->cloneIgnitionArray($cloneNonStandartSequence);
                    $x = count($cloneParallelSequence) - 1;
                } else {
                    $x--;
                }
            }
        }
        return $result;
    }

    private function nextIsNumber($cloneParallelSequence, $x) {
        if ($x + 1 >= count($cloneParallelSequence)) {
            return false;
        } else if ($cloneParallelSequence[$x + 1] == "o") {
            return false;
        } else {
            return true;
        }
    }

    private function cloneIgnitionArray($ignitionArray) {

        $result = array();
        foreach ($ignitionArray as $ignitionCode) {
            $startTime = $ignitionCode->getStartTime();
            $starterTrip = $ignitionCode->getStarterTrip();
            $cloneIgnitonCode = new BusTripIgnitionCode($starterTrip, $startTime);
            array_push($result, $cloneIgnitonCode);
        }
        return $result;
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
