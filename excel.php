<?php
include_once 'Controller/ExcelController.php';
$excelController = new excelController;
$excelResultArray = $excelController->getExcel();

$exportRows = array();
$export = false;
if (isset($_POST["export"])) {
    $export = true;
}

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
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            table, th, td {
                border: 1px solid black;
            }
        </style>
    </head>
    <body>
        <a href="uploadForm.php">ახალი ფაილის ატვირთვა</a> &nbsp;&nbsp;<a href="index.php" target="_blank">მთავარ გვერძე დაბრნუნება</a>
        <?php
        echo "<h1>გადალაგებული</h1><hr>";
        $tableArchitect = "";
        foreach ($excelResultArray as $routeDayReport) {
            $tableArchitect .= "<form action='export.php' method='POST'><input type='hidden' name='export'>"
                    . "<input type='submit' value='ექსეში ექსფორტი'><hr></form>"
                    . "<table>";
            $busTrips = $routeDayReport->getBusTrips();
            foreach ($busTrips as $busTrip) {

                $tripPeriods = $busTrip->getTripPeriods();
                $busTripNumber = $busTrip->getNumber();
                $tableArchitect .= "<tr><th colspan='7'> გასვლა #" . $busTripNumber . "</th>"
                        . "</tr>"
                        . "<tr>"
                        . "<th colspan='3'>გასვლის დრო</th>"
                        . "<th></th>"
                        . "<th colspan='3'>მისვლის დრო</th>"
                        . "</tr>"
                        . "<tr>"
                        . "<th>გეგმიუირი</th>"
                        . "<th>ფაქთიური</th>"
                        . "<th>სხვაობა</th>"
                        . "<th>------</th>"
                        . "<th>გეგმიუირი</th>"
                        . "<th>ფაქთიური</th>"
                        . "<th>სხვაობა</th>"
                        . "</tr>";

                foreach ($tripPeriods as $tripPeriod) {

                    $tripPeriodColor = $tripPeriod->getColor();
                    $tableArchitect .= "<tr style='background-color:" . $tripPeriodColor . "'>";
                    $tripPeriodType = $tripPeriod->getType();
                    $startTimeScheduled = $tripPeriod->getStartTimeScheduled();
                    $startTimeActual = $tripPeriod->getStartTimeActual();
                    $startTimeDifference = $tripPeriod->getStartTimeDifference();
                    $arrivalTimeScheduled = $tripPeriod->getArrivalTimeScheduled();
                    $arrivalTimeActual = $tripPeriod->getArrivalTimeActual();
                    $arrivalTimeDifference = $tripPeriod->getArrivalTimeDifference();
                    $tableArchitect .= "<td>" . $startTimeScheduled . "</td>";
                    $tableArchitect .= "<td>" . $startTimeActual . "</td>";
                    $tableArchitect .= "<td>" . $startTimeDifference . "</td>";

                    $tableArchitect .= "<td>------</td>";

                    $tableArchitect .= "<td>" . $arrivalTimeScheduled . "</td>";
                    $tableArchitect .= "<td>" . $arrivalTimeActual . "</td>";
                    $tableArchitect .= "<td>" . $arrivalTimeDifference . "</td>";


                    $tableArchitect .= "</tr>";
                }
                $tableArchitect .= "<tr></tr>";
            }

            $tableArchitect .= "</table>";
           
            echo $tableArchitect;
        }
        ?>
    </body>
</html>
