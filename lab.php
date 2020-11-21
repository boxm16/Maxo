
<?php
require_once 'Controller/DayTrips.php';
if (!empty($_POST)) {
    $firstTripStartTime = $_POST["firstTripStartTime"];
    $lastTripStartTime = $_POST["lastTripStartTime"];
    $aTripTimeMinute = $_POST["aTripTimeMinute"];
    $aTripTimeSecond = $_POST["aTripTimeSecond"];
    $bTripTimeMinute = $_POST["bTripTimeMinute"];
    $bTripTimeSecond = $_POST["bTripTimeSecond"];
    $abBusCount = $_POST["abBusCount"];
    $baBusCount = $_POST["baBusCount"];
    $intervalTimeMinute = $_POST["intervalTimeMinute"];
    $intervalTimeSecond = $_POST["intervalTimeSecond"];
    $breakTime = $_POST["breakTime"];
 
} else {
    $firstTripStartTime = "08:00:00";
    $lastTripStartTime = "21:00:00";
    $aTripTimeMinute = 55;
    $aTripTimeSecond = 00;
    $bTripTimeMinute = 55;
    $bTripTimeSecond = 00;
    $abBusCount = 4;
    $baBusCount = 4;
    $intervalTimeMinute = 15;
    $intervalTimeSecond = 00;
    $breakTime = 30;
}

$DayTrips = new DayTrips($firstTripStartTime, $lastTripStartTime, $aTripTimeMinute, $aTripTimeSecond, $bTripTimeMinute, $bTripTimeSecond, $abBusCount, $baBusCount, $intervalTimeMinute, $intervalTimeSecond, $breakTime);

$dayTrips = $DayTrips->getDayTrips();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>MM</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <style>
            input[type="number"] {
                width:60px;
            }
            table, tr, td, th {
                border:1px solid black;
            }
        </style>

    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <a href="index.php">უკან დაბრუნება</a> 

                    <hr>
                    <form action="lab.php" method="POST" >
                        <table class="table">
                            <thead>
                                <tr>

                                    <th colspan="2">პერიოდი</th>
                                    <th colspan="2">A წირის დრო<br>წუთებში</th>
                                    <th colspan="2">B წირის დრო<br>წუთებში</th>
                                    <th>A-B <br>ავტობუსების<br> რაოდენობა </th>
                                    <th>B-A <br>ავტობუსების<br> რაოდენობა</th>
                                    <th colspan="2">ინტერვალის<br> დრო</th>
                                    <th >შესვენება</th>
                                </tr>
                            </thead>
                            <tr><td>
                                    <input name="firstTripStartTime" type="time" step="1" value="<?php echo $firstTripStartTime ?>">
                                </td>
                                <td>
                                    <input name="lastTripStartTime" type="time" step="1" value="<?php echo $lastTripStartTime ?>">
                                </td>
                                <td><input name="aTripTimeMinute" type="number" value="<?php echo $aTripTimeMinute ?>" step="any"></td>
                                <td><input name="aTripTimeSecond" type="number" value="<?php echo $aTripTimeSecond ?>" step="any"></td>
                                <td><input name="bTripTimeMinute" type="number" value="<?php echo $bTripTimeMinute ?>" step="any"></td>
                                <td><input name="bTripTimeSecond" type="number" value="<?php echo $bTripTimeSecond ?>" step="any"></td>
                                <td><input name="abBusCount" type="number" value="<?php echo $abBusCount ?>"></td>
                                <td><input name="baBusCount" type="number" value="<?php echo $baBusCount ?>"></td>
                                <td><input name="intervalTimeMinute" type="number" value="<?php echo $intervalTimeMinute ?>" step="any"></td>
                                <td><input name="intervalTimeSecond" type="number" value="<?php echo $intervalTimeSecond ?>" step="any"></td>
                                <td><input name="breakTime" type="number" value="<?php echo $breakTime ?>" step="any"></td>

                            </tr>
                        </table>


                        <button class="btn btn-primary" type="submit">გამოსახვა</button>
                    </form>
                    <hr>
                    <?php $height = 300; ?>
                    <svg width="1500" height="350">
                    <rect x="5" width="1530" height="20" style="fill:rgb(0,0,0);" />
                    <rect x="5" width="20" height="<?php echo $height ?>" style="fill:rgb(0,0,0);" />
                    <?php
                    $x = 30;
                    $y = 30;
                    $lap = 1200 / 20;
                    $time = "05";
                    for ($a = 0; $a < 21; $a++) {
                        echo " <line x1='$x' y1='20' x2='$x' y2='$height' style='stroke:rgb(0,0,0);stroke-width:1' />";
                        $tp = $x - 17;

                        $timeF = $time . ":00";
                        echo "<text x='$tp' y='15' fill='white'>$timeF</text>";
                        $time++;
                        if ($time < 10) {
                            $time = "0" . $time;
                        }
                        if ($time == 25) {
                            $time = "01";
                        }
                        $x += $lap;
                    }


//--------------------------------------------------------//
                    $yI = 30;
                    foreach ($dayTrips as $busDayTrips) {

                        foreach ($busDayTrips as $trip) {
                            $startTime = $trip->getStartTime();
                            $coverTime = $trip->getCoverTime();
                            $color = $trip->getTripColor();
                            $timeInt = intval($trip->getStartTimeInSeconds());
                            $time = "";
                            if ($timeInt > 0) {
                                $time = gmdate("H:i:s", $timeInt);
                            }

                            echo "<rect x='$startTime' y='$yI' width='$coverTime' height='20'  rx='7' style='fill:$color' />";
                            $textStartPoint = $startTime + 5;
                            $yB = $yI + 15;
                            echo "<text x='$textStartPoint' y='$yB' class='small' style='fill:black;'>" . $time . "</text>";
                        }
                        $yI += 30;
                    }
                    ?>


                    </svg>

                    <hr>
                    <?php $height = 300; ?>
                    <svg width="1500" height="400">
                    <rect x="5" width="1530" height="20" style="fill:rgb(0,0,0);" />
                    <rect x="5" width="20" height="<?php echo $height ?>" style="fill:rgb(0,0,0);" />
                    <?php
                    $x = 30;
                    $y = 30;
                    $lap = 1200 / 20;
                    $time = "05";
                    for ($a = 0; $a < 21; $a++) {
                        echo " <line x1='$x' y1='20' x2='$x' y2='$height' style='stroke:rgb(0,0,0);stroke-width:1' />";
                        $tp = $x - 17;

                        $timeF = $time . ":00";
                        echo "<text x='$tp' y='15' fill='white'>$timeF</text>";
                        $time++;
                        if ($time < 10) {
                            $time = "0" . $time;
                        }
                        if ($time == 25) {
                            $time = "01";
                        }
                        $x += $lap;
                    }


//--------------------------------------------------------//
                    $yI = 30;

                    $dayTripsWithBreak = $DayTrips->getDayTripsWithBreak();
                    foreach ($dayTripsWithBreak as $busDayTrips) {

                        foreach ($busDayTrips as $trip) {
                            $startTime = $trip->getStartTime();
                            $coverTime = $trip->getCoverTime();
                            $color = $trip->getTripColor();
                            $timeInt = intval($trip->getStartTimeInSeconds());
                            $time = "";
                            if ($timeInt > 0) {
                                $time = gmdate("H:i:s", $timeInt);
                            }

                            echo "<rect x='$startTime' y='$yI' width='$coverTime' height='20'  rx='7' style='fill:$color' />";
                            $textStartPoint = $startTime + 5;
                            $yB = $yI + 15;
                            echo "<text x='$textStartPoint' y='$yB' class='small' style='fill:black;'>" . $time . "</text>";
                        }
                        $yI += 30;
                    }
                    ?>


                    </svg>
                    <hr>
                    <div>
                        <?php echo $DayTrips->getBreaksPoolCount(); ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
