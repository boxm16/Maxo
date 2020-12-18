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
        echo date("h:i:s");
        echo "<hr>";
       $a = array("1", "2", "D","1", "2", "D", "1", "2", "D");
        $b = array("1", "2", "D", "1", "2", "D", "1", "2", "D");
        $c = array("1", "2", "D", "1", "2", "D","1", "2", "D");
        $d = array("1", "2", "D", "1", "2", "D","1", "2", "D", "2", "D", "1", "2", "D","1", "2", "D");
        $e = array("1", "2", "D", "1", "2", "D","2", "D", "1", "2", "D","1", "2", "D");
        $f = array("1", "2", "D", "1", "2", "D", "2", "D", "1", "2", "D","1", "2", "D");
        $g = array("1", "2", "D", "1", "2", "D", "2", "D", "1", "2", "D","1", "2", "D");
        $h = array("1", "2", "D", "1", "2", "D", "2", "D", "1", "2", "D","1", "2", "D");
        $bigArray = array($a, $b, $c, $d, $e, $f, $g, $h);
        printArray($bigArray);
        echo "<hr>";
        permuteRow(0, $bigArray, "");
        
        echo "<hr>";

        echo date("h:i:s");
        echo "<hr>";
    

        function permuteRow($RowNumber, $bigArray, $initialNumber) {

            $arr = $bigArray[$RowNumber];
            for ($a = 0; $a < count($arr); $a++) {
                $number = $initialNumber . $arr[$a];
                if ($RowNumber == count($bigArray) - 1) {
                  
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
