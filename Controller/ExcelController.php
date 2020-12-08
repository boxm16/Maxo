<?php

include "SimpleXLSX.php";
require_once 'Model/RouteDayReportXL.php';
require_once 'Model/BusTripXL.php';
require_once 'Model/BusXL.php';
require_once 'Model/DriverXL.php';
require_once 'Model/TripPeriodXL.php';
require_once 'TimeController.php';

class ExcelController {

    private $rowIndex;

    public function getExcel() {
        $routeReportsArray = array();
        $routeNumber = 0;
        $busTripNumber = 0;
        if ($xlsx = SimpleXLSX::parse('uploads/excellFile.xlsx')) {
            $rows = $xlsx->rowsEx();

            $this->rowIndex = 0;
            $routeNumber = 0;
            while ($this->rowIndex < count($rows)) {

                $row = $rows[$this->rowIndex];
                if ($row[7]["value"] == "მარშრუტი" || $row[7]["value"] == "") {
                    $this->rowIndex++;
                    continue;
                }
                if ($row[7]["value"] != $routeNumber) {
                    $routeNumber = $row[7]["value"];
                    $date = $row[5]["value"];
                    $baseNumber = $row[0]["value"];
                    $routeDayReport = new RouteDayReportXL($routeNumber, $date, $baseNumber);

                    //first create BusTrip
                    $busTripNumber = $row[8]["value"];
                    $busTrip = $this->createBusTrip($row, $busTripNumber);
                    $routeDayReport->addBusTripToBusTripsArray($busTrip);
                    //now create baseOutTripPeriod;
                    $tripPeriod = $this->createBaseLeavingPeriod($row);
                    $busTrip->addTripPeriodToTripPeriodsArray($tripPeriod);

                    array_push($routeReportsArray, $routeDayReport);
                } else {
                    if ($busTripNumber == $row[8]["value"]) {

                        $busTrips = $routeDayReport->getBusTrips();
                        $busTrip = $busTrips[count($busTrips) - 1];
                        $busTripPeriodsOnTheRow = $this->getbusTripPeriodsOnTheRow($row);

                        foreach ($busTripPeriodsOnTheRow as $tripPeriod) {
                            $busTrip->addTripPeriodToTripPeriodsArray($tripPeriod);
                        }
                    } else {
                        //repeting busTrip CXreation process, maybe i`ll collect this in a method
                        $busTripNumber = $row[8]["value"];
                        $busTrip = $this->createBusTrip($row, $busTripNumber);
                        $routeDayReport->addBusTripToBusTripsArray($busTrip);
                        //now create baseOutTripPeriod;
                        $tripPeriod = $this->createBaseLeavingPeriod($row);

                        $busTrip->addTripPeriodToTripPeriodsArray($tripPeriod);
                    }
                }

                /* foreach ($row as $cell) {
                  var_dump($cell);
                  echo "<hr>";
                  }
                  echo "<hr>";
                  echo "<hr>";
                  echo "<hr>";
                 */
                $this->rowIndex++;
            }
        } else {
            echo SimpleXLSX::parseError();
        }
        return $routeReportsArray;
    }

    private function getBaseLeavingTimeScheduled($row) {
        if ($row[14]["value"] != "") {
            return $row[14]["value"];
        } else {
            return $row[20]["value"];
        }
    }

    private function getBaseLeavingTimeActual($row) {
        if ($row[14]["value"] != "") {
            return $row[15]["value"];
        } else {
            return $row[21]["value"];
        }
    }

    private function getBaseLeavingTimeDifference($row) {
        if ($row[14]["value"] != "") {
            return $row[16]["value"];
        } else {
            return $row[22]["value"];
        }
    }

    private function getBaseArrivaTimeScheduled($row) {
        if ($row[14]["value"] != "") {
            return $row[17]["value"];
        } else {
            return $row[23]["value"];
        }
    }

    private function getBaseArrivalTimeActual($row) {
        if ($row[14]["value"] != "") {
            return $row[18]["value"];
        } else {
            return $row[24]["value"];
        }
    }

    private function getBaseArrivalTimeDifference($row) {
        if ($row[14]["value"] != "") {
            return $row[19]["value"];
        } else {
            return $row[25]["value"];
        }
    }

    private function createBaseLeavingPeriod($row) {

        $tripPeriodType = "baseLeaving";
        $tripPeriodStartTimeScheduled = $this->getBaseLeavingTimeScheduled($row);
        $tripPeriodsStartTimeActual = $this->getBaseLeavingTimeActual($row);
        $tripPeriodStartTimeDifference = $this->getBaseLeavingTimeDifference($row);
        $tripPeriodArrivalTimeScheduled = $this->getBaseArrivaTimeScheduled($row);
        $tripPeriodArrivalTimeActual = $this->getBaseArrivalTimeActual($row);
        $tripPeriodArrivalTimeDifference = $this->getBaseArrivalTimeDifference($row);

        $tripPeriod = new TripPeriodXL($tripPeriodType, $tripPeriodStartTimeScheduled, $tripPeriodsStartTimeActual, $tripPeriodStartTimeDifference, $tripPeriodArrivalTimeScheduled, $tripPeriodArrivalTimeActual, $tripPeriodArrivalTimeActual, $tripPeriodArrivalTimeDifference);
        return $tripPeriod;
    }

    private function createBusTrip($row, $busTripNumber) {
        $busType = $row[1]["value"];
        $busPlateNumber = $row[2]["value"];
        $bus = new BusXL($busType, $busPlateNumber);
        //now driver
        $driverNumber = $row[3]["value"];
        $driverName = $row[4]["value"];
        $driver = new DriverXL($driverNumber, $driverName);

        $reportNumber = $row[1]["value"];
        $baseLeavingTimeScheduled = $row[9]["value"];
        $baseLeavingTimeActual = $row[10]["value"];
        $baseReturnTimeScheduled = $row[11]["value"];
        $baseReturnTimeActal = $row[12]["value"];

        $busTrip = new BusTripXL($busTripNumber, $bus, $driver, $reportNumber, $baseLeavingTimeScheduled, $baseLeavingTimeActual, $baseReturnTimeScheduled, $baseReturnTimeActal);

        return $busTrip;
    }

    private function getBusTripPeriodsOnTheRow($row) {
        $busTripRound = array();
        $tripPeriodType = $this->identifyBusTripPeriodOnRow($row);
        if ($tripPeriodType == "baseLeaving") {
            $tripPeriod = $this->createBaseLeavingPeriod($row);
            $tripPeriodsArray = array();
            array_push($tripPeriodsArray, $tripPeriod);
            return $tripPeriodsArray;
        }
        if ($tripPeriodType == "break") {
            $tripPeriod = $this->createBreakPeriod($row);
            $tripPeriodsArray = array();
            array_push($tripPeriodsArray, $tripPeriod);
            return $tripPeriodsArray;
        }
        if ($tripPeriodType == "baseReturn") {
            $tripPeriod = $this->createBaseReturnPeriod($row);
            $tripPeriodsArray = array();
            array_push($tripPeriodsArray, $tripPeriod);
            return $tripPeriodsArray;
        }
        if ($tripPeriodType == "round") {
            $tripPeriodsArray = array();
            if ($row[14]["value"] != "" && $row[20]["value"] != "") {
                $leftSideTime = $row[14]["value"];
                $rightSideTime = $row[20]["value"];
                $timeController = new TimeController();

                $leftSideTimeInSeconds = $timeController->getSecondsFromTimeStamp($leftSideTime);
                $rightSideTimeInSeconds = $timeController->getSecondsFromTimeStamp($rightSideTime);
                if ($leftSideTime < $rightSideTime) {
                    $tripPeriod = $this->getLeftSideTripOnTheRow($row);
                    array_push($tripPeriodsArray, $tripPeriod);
                    $tripPeriod = $this->getRightSideTripOnTheRow($row);
                    array_push($tripPeriodsArray, $tripPeriod);
                    return $tripPeriodsArray;
                } else {
                    $tripPeriod = $this->getRightSideTripOnTheRow($row);
                    array_push($tripPeriodsArray, $tripPeriod);
                    $tripPeriod = $this->getLeftSideTripOnTheRow($row);
                    array_push($tripPeriodsArray, $tripPeriod);
                    return $tripPeriodsArray;
                }
            }
            if ($row[14]["value"] != "") {
                $tripPeriod = $this->getLeftSideTripOnTheRow($row);
                array_push($tripPeriodsArray, $tripPeriod);
            }
            if ($row[20]["value"] != "") {
                $tripPeriod = $this->getRightSideTripOnTheRow($row);
                array_push($tripPeriodsArray, $tripPeriod);
            }return $tripPeriodsArray;
        }
    }

    private function identifyBusTripPeriodOnRow($row) {
        $busTripName = $row[13]["value"];
        if (strpos($busTripName, "გასვლა") != null) {
            return "baseLeaving";
        }
        if (strpos($busTripName, "შესვენება") != null) {
            return "break";
        }
        if (strpos($busTripName, "დაბრუნება") != null) {
            return "baseReturn";
        }
        if (strpos($busTripName, "ბრუნი") != null) {
            return "round";
        }
    }

    private function createBaseReturnPeriod($row) {

        $tripPeriodType = "baseReturn";
        $tripPeriodStartTimeScheduled = $this->getBaseLeavingTimeScheduled($row);
        $tripPeriodsStartTimeActual = $this->getBaseLeavingTimeActual($row);
        $tripPeriodStartTimeDifference = $this->getBaseLeavingTimeDifference($row);
        $tripPeriodArrivalTimeScheduled = $this->getBaseArrivaTimeScheduled($row);
        $tripPeriodArrivalTimeActual = $this->getBaseArrivalTimeActual($row);
        $tripPeriodArrivalTimeDifference = $this->getBaseArrivalTimeDifference($row);

        $tripPeriod = new TripPeriodXL($tripPeriodType, $tripPeriodStartTimeScheduled, $tripPeriodsStartTimeActual, $tripPeriodStartTimeDifference, $tripPeriodArrivalTimeScheduled, $tripPeriodArrivalTimeActual, $tripPeriodArrivalTimeActual, $tripPeriodArrivalTimeDifference);
        return $tripPeriod;
    }

    private function createBreakPeriod($row) {

        $tripPeriodType = "break";
        $tripPeriodStartTimeScheduled = $this->getBaseLeavingTimeScheduled($row);
        $tripPeriodsStartTimeActual = $this->getBaseLeavingTimeActual($row);
        $tripPeriodStartTimeDifference = $this->getBaseLeavingTimeDifference($row);
        $tripPeriodArrivalTimeScheduled = $this->getBaseArrivaTimeScheduled($row);
        $tripPeriodArrivalTimeActual = $this->getBaseArrivalTimeActual($row);
        $tripPeriodArrivalTimeDifference = $this->getBaseArrivalTimeDifference($row);

        $tripPeriod = new TripPeriodXL($tripPeriodType, $tripPeriodStartTimeScheduled, $tripPeriodsStartTimeActual, $tripPeriodStartTimeDifference, $tripPeriodArrivalTimeScheduled, $tripPeriodArrivalTimeActual, $tripPeriodArrivalTimeActual, $tripPeriodArrivalTimeDifference);
        return $tripPeriod;
    }

    private function getLeftSideTripOnTheRow($row) {
        $tripPeriodType = "ab";
        $tripPeriodStartTimeScheduled = $row[14]["value"];
        $tripPeriodsStartTimeActual = $row[15]["value"];
        $tripPeriodStartTimeDifference = $row[16]["value"];
        $tripPeriodArrivalTimeScheduled = $row[17]["value"];
        $tripPeriodArrivalTimeActual = $row[18]["value"];
        $tripPeriodArrivalTimeDifference = $row[19]["value"];

        $tripPeriod = new TripPeriodXL($tripPeriodType, $tripPeriodStartTimeScheduled, $tripPeriodsStartTimeActual, $tripPeriodStartTimeDifference, $tripPeriodArrivalTimeScheduled, $tripPeriodArrivalTimeActual, $tripPeriodArrivalTimeActual, $tripPeriodArrivalTimeDifference);
        return $tripPeriod;
    }

    private function getRightSideTripOnTheRow($row) {
        $tripPeriodType = "ba";
        $tripPeriodStartTimeScheduled = $row[20]["value"];
        $tripPeriodsStartTimeActual = $row[21]["value"];
        $tripPeriodStartTimeDifference = $row[22]["value"];
        $tripPeriodArrivalTimeScheduled = $row[23]["value"];
        $tripPeriodArrivalTimeActual = $row[24]["value"];
        $tripPeriodArrivalTimeDifference = $row[25]["value"];

        $tripPeriod = new TripPeriodXL($tripPeriodType, $tripPeriodStartTimeScheduled, $tripPeriodsStartTimeActual, $tripPeriodStartTimeDifference, $tripPeriodArrivalTimeScheduled, $tripPeriodArrivalTimeActual,$tripPeriodArrivalTimeDifference);
        return $tripPeriod;
    }

}
