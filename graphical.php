<?php
require_once 'Controller/RouteController.php';
if (!empty($_POST)) {
    $starterTrip = $_POST["starterTrip"];
    $firstTripStartTime = $_POST["firstTripStartTime"];
    $lastTripStartTime = $_POST["lastTripStartTime"];
    $abTripTimeMinutes = $_POST["abTripTimeMinutes"];
    $abTripTimeSeconds = $_POST["abTripTimeSeconds"];
    $baTripTimeMinutes = $_POST["baTripTimeMinutes"];
    $baTripTimeSeconds = $_POST["baTripTimeSeconds"];
    $abBusCount = $_POST["abBusCount"];
    $baBusCount = $_POST["baBusCount"];
    $intervalTimeMinutes = $_POST["intervalTimeMinutes"];
    $intervalTimeSeconds = $_POST["intervalTimeSeconds"];
    $breakTimeMinutes = $_POST["breakTimeMinutes"];
    $breakTimeSeconds = $_POST["breakTimeSeconds"];
} else {
    $starterTrip = "ab";
    $firstTripStartTime = "08:00:00";
    $lastTripStartTime = "21:00:00";
    $abTripTimeMinutes = 55;
    $abTripTimeSeconds = 00;
    $baTripTimeMinutes = 55;
    $baTripTimeSeconds = 00;
    $abBusCount = 4;
    $baBusCount = 4;
    $intervalTimeMinutes = 15;
    $intervalTimeSeconds = 00;
    $breakTimeMinutes = 30;
    $breakTimeSeconds = 00;
}
require_once 'Controller/RouteController.php';
$routeController = new RouteController($starterTrip, $firstTripStartTime, $lastTripStartTime, $abTripTimeMinutes, $abTripTimeSeconds, $baTripTimeMinutes, $baTripTimeSeconds, $abBusCount, $baBusCount, $intervalTimeMinutes, $intervalTimeSeconds, $breakTimeMinutes, $breakTimeSeconds);
$allVersions = $routeController->getAllVersions();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>გრაფიკული გამოსახვა</title>
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

                    <form action="graphical.php" method="POST" >
                        <table class="table">
                            <thead>
                                <tr>

                                    <th colspan="3">პერიოდი</th>
                                    <th colspan="2">A-B წირის დრო</th>
                                    <th colspan="2">B-A წირის დრო</th>
                                    <th>A-B <br>ავტობუსების<br> რაოდენობა </th>
                                    <th>B-A <br>ავტობუსების<br> რაოდენობა</th>
                                    <th colspan="2">ინტერვალის<br> დრო</th>
                                    <th colspan="2" >შესვენება</th>
                                </tr>
                            </thead>
                            <tr>
                                <td>
                                    A_B &nbsp;<input type="radio" name="starterTrip" value="ab" <?php if ($starterTrip == "ab") echo "checked"; ?> 
                                                     >
                                    <br>
                                    B_A &nbsp;<input type="radio" name="starterTrip" value="ba" <?php  if ($starterTrip == "ba") echo "checked"; ?>>
                                </td>
                                <td>
                                    <input name="firstTripStartTime" type="time" step="1" value="<?php echo $firstTripStartTime ?>">
                                </td>
                                <td>
                                    <input name="lastTripStartTime" type="time" step="1" value="<?php echo $lastTripStartTime ?>">
                                </td>
                                <td>
                                    <input name="abTripTimeMinutes" type="number" value="<?php echo $abTripTimeMinutes ?>" step="any"></td>
                                <td>
                                    <input name="abTripTimeSeconds" type="number" value="<?php echo $abTripTimeSeconds ?>" step="any">
                                </td>
                                <td>
                                    <input name="baTripTimeMinutes" type="number" value="<?php echo $baTripTimeMinutes ?>" step="any">
                                </td>
                                <td>
                                    <input name="baTripTimeSeconds" type="number" value="<?php echo $baTripTimeSeconds ?>" step="any">
                                </td>
                                <td>
                                    <input name="abBusCount" type="number" value="<?php echo $abBusCount ?>">
                                </td>
                                <td>
                                    <input name="baBusCount" type="number" value="<?php echo $baBusCount ?>">
                                </td>
                                <td>
                                    <input name="intervalTimeMinutes" type="number" value="<?php echo $intervalTimeMinutes ?>" step="any">
                                </td>
                                <td>
                                    <input name="intervalTimeSeconds" type="number" value="<?php echo $intervalTimeSeconds ?>" step="any">
                                </td>
                                <td>
                                    <input name="breakTimeMinutes" type="number" value="<?php echo $breakTimeMinutes ?>" step="any">
                                </td>
                                <td>
                                    <input name="breakTimeSeconds" type="number" value="<?php echo $breakTimeSeconds ?>" step="any">
                                </td>

                            </tr>
                        </table>


                        <button class="btn btn-primary" type="submit">გამოსახვა</button>
                    </form>
                    <hr>
                    <?php
                    foreach ($allVersions as $routeVersion) {

                        $height = 300;
                        $x = 30;
                        $y = 30;
                        $lap = 1200 / 20;
                        $time = "05";
                        echo "<svg width='1500' height='400'>"
                        . "<rect x='5' width='1530' height='20' style='fill:rgb(0,0,0);' />"
                        . "<rect x='5' width='20' height=$height style='fill:rgb(0,0,0);' />";

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
                        //----------------------------------------------------------------
                        $yI = 30;
                        foreach ($routeVersion as $busTrip) {
                            $tripPeriods = $busTrip->getTripPeriods();
                            foreach ($tripPeriods as $tripPeriod) {
                                $startPoint = $tripPeriod->getStartPoint();
                                $length = $tripPeriod->getLength();
                                $color = $tripPeriod->getPeriodColor();
                                $timeInt = intval($tripPeriod->getStartTimeInSeconds());
                                $time = "";
                                if ($timeInt > 0) {
                                    $time = gmdate("H:i:s", $timeInt);
                                }

                                echo "<rect x='$startPoint' y='$yI' width='$length' height='20'  rx='7' style='fill:$color' />";
                                $textStartPoint = $startPoint + 5;
                                $yB = $yI + 15;
                                echo "<text x='$textStartPoint' y='$yB' class='small' style='fill:white;'>" . $time . "</text>";
                            }
                            $yI += 30;
                        }

                        echo '</svg>'
                        . '<hr>';
                    }
                    ?>

                </div>

            </div>
        </div>
        <script>

        </script>
    </body>
</html>
