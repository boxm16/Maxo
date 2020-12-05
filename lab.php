

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
                    <?php
                    include 'Model/TripPeriod.php';
                    $type = "A";
                    $startPoint = 205;
                    $startTimeInSeconds = "1000";
                    $length = "5";
                    $tripPeriod1 = new TripPeriod($type, $startPoint, $startTimeInSeconds, $length);
                    $type1 = "B";
                    $startPoint1 = 505;
                    $startTimeInSeconds1 = "2000";
                    $length1 = "55";
                    $tripPeriod2 = new TripPeriod($type1, $startPoint1, $startTimeInSeconds1, $length1);

                    $tripArray = array();
                    array_push($tripArray, $tripPeriod1);
                    array_push($tripArray, $tripPeriod2);
                    foreach ($tripArray as $item) {
                        var_dump($item);
                        echo "<hr>";
                    }

                    $arrayCope = cloneArrayOfObjects($tripArray);

                    $tripOne = $tripArray[0];
                    $tripOne->setType("CCCCC");

                    $tripTWo = $arrayCope[1];
                    $tripTWo->setType("DDDDDDD");

                    var_dump($arrayCope);

                    foreach ($tripTWo as $item) {
                        var_dump($item);
                        echo "<hr>";
                    }

                    function cloneArrayOfObjects($array) {
                        $clonedArray = array();
                        foreach ($array as $tripPeriod) {
                            $type = $tripPeriod->getType();
                            $clonedTripPeriod = clone $tripPeriod;
                            $clonedTripPeriod->setType($type);
                            array_push($clonedArray, $clonedTripPeriod);
                        }
                        return $clonedArray;
                    }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
