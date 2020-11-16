
<?php
require_once 'Controller/DayTrips.php';
if (!empty($_POST)) {
    $firstTripStartTime = $_POST["firstTripStartTime"];
    $lastTripStartTime = $_POST["lastTripStartTime"];
    $aTripTime = $_POST["aTripTime"];
    $bTripTime = $_POST["bTripTime"];
    $abBusCount = $_POST["abBusCount"];
    $baBusCount = $_POST["baBusCount"];
    $intervalTime = $_POST["intervalTime"];
} else {
    $firstTripStartTime = "08:00";
    $lastTripStartTime = "21:00";
    $aTripTime = 55;
    $bTripTime = 55;
    $abBusCount = 4;
    $baBusCount = 4;
    $intervalTime = 15;
}
$DayTrips = new DayTrips($firstTripStartTime, $lastTripStartTime, $aTripTime, $bTripTime, $abBusCount, $baBusCount, $intervalTime);

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
                                    <th>A წირის დრო<br>წუთებში</th>
                                    <th>B წირის დრო<br>წუთებში</th>
                                    <th>A-B <br>ავტობუსების<br> რაოდენობა </th>
                                    <th>B-A <br>ავტობუსების<br> რაოდენობა</th>
                                    <th>ინტერვალის<br> დრო</th>
                                </tr>
                            </thead>
                            <tr><td>
                                    <input name="firstTripStartTime" type="time" value="<?php echo $firstTripStartTime ?>">
                                </td>
                                <td>
                                    <input name="lastTripStartTime" type="time" value="<?php echo $lastTripStartTime ?>">
                                </td>
                                <td><input name="aTripTime" type="number" value="<?php echo $aTripTime ?>" step="any"></td>
                                <td><input name="bTripTime" type="number" value="<?php echo $bTripTime ?>" step="any"></td>
                                <td><input name="abBusCount" type="number" value="<?php echo $abBusCount ?>"></td>
                                <td><input name="baBusCount" type="number" value="<?php echo $baBusCount ?>"></td>
                                <td><input name="intervalTime" type="number" value="<?php echo $intervalTime ?>" step="any"></td>
                            </tr>
                        </table>


                        <button class="btn btn-primary" type="submit">გამოსახვა</button>
                    </form>
                    <hr>
                    <?php $height = 300; ?>
                    <svg width="1500" height="610">
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

                            echo "<rect x='$startTime' y='$yI' width='$coverTime' height='20'  rx='7' style='fill:$color' />";
                           $textStartPoint=$startTime+3;
                           $yB=$yI+15;
                            echo "<text x='$textStartPoint' y='$yB' class='small' style='fill:white;'>".$trip->getInsideText()."</text>";
                        }
                        $yI += 30;
                    }
                    ?>


                    </svg>
                    <?php
                    foreach ($dayTrips as $busDayTrip) {
                        
                    }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
