<?php

include_once 'Controller/ExcelController.php';
$excelController = new excelController;
$excelResultArray = $excelController->getExcel();

$bigArray = array();
foreach ($excelResultArray as $routeDayReport) {

    $busTrips = $routeDayReport->getBusTrips();
    foreach ($busTrips as $busTrip) {

        $tripPeriods = $busTrip->getTripPeriods();
        $busTripNumber = $busTrip->getNumber();
        $smallArray = array("--------", "--------", "--------", $busTripNumber . " gasvla", "--------", "--------");
        array_push($bigArray, $smallArray);
        $smallArray = array("gegmiuri gasvla", "faktiurei gasvla", "sxvaoba", "gegmiuri misvla", "faktiuri misvla", "sxvaoba");
        array_push($bigArray, $smallArray);
        foreach ($tripPeriods as $tripPeriod) {

            $tripPeriodColor = $tripPeriod->getColor();
            $tripPeriodType = $tripPeriod->getType();
            $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
            $startTimeActual = $tripPeriod->getStartTimeActual();
            $startTimeDifference = $tripPeriod->getStartTimeDifference();
            $arrivalTimeScheduled = $tripPeriod->getArrivalTimeScheduled();
            $arrivalTimeActual = $tripPeriod->getArrivalTimeActual();
            $arrivalTimeDifference = $tripPeriod->getArrivalTimeDifference();
            $smallArray = array($startTimeScheduled, $startTimeActual, $startTimeDifference, $arrivalTimeScheduled, $arrivalTimeActual, $arrivalTimeDifference);
            array_push($bigArray, $smallArray);
        }
    }
}


exportProductDatabase($bigArray);

function exportProductDatabase($rows) {
    $timestamp = time();
    $filename = 'Export_excel_' . $timestamp . '.xls';

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $isPrintHeader = false;
    foreach ($rows as $row) {
        if (!$isPrintHeader) {
            echo implode("\t", array_keys($row)) . "\n";
            $isPrintHeader = true;
        }
        echo implode("\t", array_values($row)) . "\n";
    }
    exit();
}
?>
