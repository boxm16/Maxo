<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>

        <?php
        $a = array("1", "2", "3", "D");
        $b = array("4", "2", "3", "D");
        $c = array("5", "6", "7", "D");
        $d = array("9", "2", "3", "D");
        $e = array("1", "2", "3", "D");
        $f = array("4", "2", "3", "D");
        $g = array("5", "6", "7", "D");
        $h = array("9", "2", "3", "D");
        $bigArray = array($a, $b, $c, $d, $e, $f, $g, $h);
        printArray($bigArray);
        echo "<hr>";
        permuteRow(0, $bigArray, "");

        function permuteRow($RowNumber, $bigArray, $initialNumber) {

            $arr = $bigArray[$RowNumber];
            for ($a = 0; $a < count($arr); $a++) {
                $number = $initialNumber . $arr[$a];
                if ($RowNumber == count($bigArray) - 1) {
                    echo $number;
                    echo "<br>";
                }
                $nextRow = $RowNumber + 1;
                if ($nextRow < count($bigArray)) {
                    permuteRow($nextRow, $bigArray, $number);
                }
            }
        }

        function printArray($bigArray) {
            foreach ($bigArray as $array) {
                foreach ($array as $item) {
                    echo $item;
                }
                echo "<br>";
            }
        }
        ?>
    </body>
</html>
