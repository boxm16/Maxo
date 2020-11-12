
<?php
require_once 'Controller/DayTrips.php';
$DayTrips = new DayTrips();
$dayTrips = $DayTrips->getDayTrips();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bootstrap Example</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


    </head>
    <body>

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
            }
            $yI += 30;
        }
        ?>


        </svg>
        <?php
        foreach ($dayTrips as $busDayTrip) {
            
        }
        ?>
    </body>
</html>
