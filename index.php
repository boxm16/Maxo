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
        <style>
            input[type="number"] {
                width:34px;
            }
            table {
                border-collapse: collapse;
            }   

            table,  td , th{
                border: 1px solid black;
            }

        </style>
    </head>
    <body>
        <a href="try.php">ახალი ვერსიის სანახავად დააჭირე აქ</a>
        <br>
        <table >
            <thead>
                <tr>
                    <th>აირჩიე 2 <br> პარამეტრი</th>
                    <th colspan="4">ცვლადი პარამეტრები</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input id="timeCheckBox" type="checkbox" onclick="checkTimeInput()"></td>
                    <td >ბრუნის დრო</td><td>საათი<br><input id="timeInputHour" type="number" min="0" disabled="true" value="02" onchange="calculateByTime()" onkeyup="calculateByTime()"></td><td>წუთი<br><input id="timeInputMinute" type="number" disabled="true" value="00" onchange="calculateByTime()" onkeyup="calculateByTime()"></td><td>წამი<br><input id="timeInputSecond" type="number" disabled="true" value="00" onchange="calculateByTime()" onkeyup="calculateByTime()"></td>

                </tr>
                <tr>
                    <td><input id="busCheckBox" type="checkbox" onclick="checkBusInput()"></td>
                    <td>ავტობუსების<br> რაოდენობა</td><td colspan="3"><input id="busInput" type="number" disabled="true" style="width:125px;" max="200" min="1" value="1" oninput="calculateByBus()"></td>
                </tr>
                <tr>
                    <td><input id="intervalCheckBox" type="checkbox" onclick="checkIntervalInput()"></td>
                    <td>ინტერვალი</td><td>საათი<br><input id="intervalInputHour" type="number" disabled="true" value="00" onchange="calculateByInterval()" onkeyup="calculateByInterval()"></td><td>წუთი<br><input id="intervalInputMinute" type="number" disabled="true" value="10" onchange="calculateByInterval()" onkeyup="calculateByInterval()"></td><td>წამი<br><input id="intervalInputSecond" type="number" disabled="true"  value="00" onchange="calculateByInterval()" onkeyup="calculateByInterval()"></td>
                </tr>
            </tbody>
        </table>
        <label id="notes"></label>
        <hr>
        <label>ბრუნის დროის +/-</label><input id="plusMinusInput" type="number" min="0" value="2" onchange="calculateTable()"><label> წუთით შეცვლის შემთხვევაში ჯერადი შედეგების ცხრილი</label>
        <br><br>
        <label>00 წამზე დამჯდარი შედეგები</label>
        <br>

        <div id="cxriliZero"></div>
        (თუ ცხრილი ცარიელია ესეიგი არ არსებოს 00 წამზე დამჯდარი არცერთი შედეგი)
        <hr>

        <label>ყველა შედეგები</label>
        <div id="cxrili"></div>

        <script>

            calculateTable();
            var notes = document.getElementById("notes");
            notes.innerHTML = "";


            var checkboxes;

            function checkTimeInput() {
                if (timeCheckBox.checked === true) {
                    if (canAddParameter()) {
                        timeInputHour.disabled = false;
                        timeInputMinute.disabled = false;
                        timeInputSecond.disabled = false;
                    } else {
                        timeCheckBox.checked = false;
                        alert("სამივე პარამეტრის არჩევა დაუშვებელია");
                    }
                } else {
                    timeInputHour.disabled = true;
                    timeInputMinute.disabled = true;
                    timeInputSecond.disabled = true;
                }
            }

            function checkBusInput() {
                if (busCheckBox.checked === true) {
                    if (canAddParameter()) {
                        busInput.disabled = false;
                    } else {
                        busCheckBox.checked = false;
                        alert("სამივე პარამეტრის არჩევა დაუშვებელია");
                    }
                } else {
                    busInput.disabled = true;
                }
            }
            function checkIntervalInput() {
                if (intervalCheckBox.checked === true) {
                    if (canAddParameter()) {
                        intervalInputHour.disabled = false;
                        intervalInputMinute.disabled = false;
                        intervalInputSecond.disabled = false;
                    } else {
                        intervalCheckBox.checked = false;
                        alert("სამივე პარამეტრის არჩევა დაუშვებელია");
                    }
                } else {
                    intervalInputHour.disabled = true;
                    intervalInputMinute.disabled = true;
                    intervalInputSecond.disabled = true;
                }
            }

            function canAddParameter() {
                checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
                if (checkboxes.length < 3) {
                    return true;
                } else {
                    return false;
                }
            }



            function   calculateByTime() {
                //plus plus and minus minus result
                if (timeInputSecond.value == 60) {
                    timeInputSecond.value = 0;
                    timeInputMinute.value = parseInt(timeInputMinute.value) + 1;
                }
                if (timeInputSecond.value == -1) {
                    timeInputSecond.value = 59;
                    timeInputMinute.value = parseInt(timeInputMinute.value) - 1;
                }


                if (timeInputMinute.value == 60) {
                    timeInputMinute.value = 0;
                    timeInputHourPlusPlus();
                }
                if (timeInputMinute.value == -1) {
                    timeInputMinute.value = 59;
                    timeInputHourMinusMinus();
                }

                //---------------
                checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
                if (checkboxes.length < 2) {
                    alert("არჩეულია არასაკარისი პარამეტრები. საჭიროა 2 პარამეტრის არჩევა");
                } else {

                    var seconds = (timeInputHour.value * 60 * 60) + (timeInputMinute.value * 60) + parseInt(timeInputSecond.value);

                    if (busCheckBox.checked === true) {
                        calculateInterval(seconds);
                    } else {
                        calculateBus(seconds);

                    }
                    calculateTable();
                }
            }
            function calculateByBus() {
                checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
                if (checkboxes.length < 2) {
                    alert("არჩეულია არასაკარისი პარამეტრები. საჭიროა 2 პარამეტრის არჩევა");
                } else {
                    var seconds = (timeInputHour.value * 60 * 60) + (timeInputMinute.value * 60) + parseInt(timeInputSecond.value);

                    if (timeCheckBox.checked === true) {
                        calculateInterval(seconds);
                    } else {
                        calculateTime();
                    }
                    calculateTable();
                }

            }
            function calculateByInterval() {
                //plus plus and minus minus result
                if (intervalInputSecond.value == 60) {
                    intervalInputSecond.value = 0;
                    intervalInputMinute.value = parseInt(intervalInputMinute.value) + 1;
                }
                if (intervalInputSecond.value == -1) {
                    intervalInputSecond.value = 59;
                    intervalInputMinute.value = parseInt(intervalInputMinute.value) - 1;
                }


                if (intervalInputMinute.value == 60) {
                    intervalInputMinute.value = 0;
                    intervalInputHourPlusPlus();
                }
                if (intervalInputMinute.value == -1) {
                    intervalInputMinute.value = 59;
                    intervalInputHourMinusMinus();
                }

                //---------------

                checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
                if (checkboxes.length < 2) {
                    alert("არჩეულია არასაკარისი პარამეტრები. საჭიროა 2 პარამეტრის არჩევა");
                } else {
                    var seconds = (timeInputHour.value * 60 * 60) + (timeInputMinute.value * 60) + parseInt(timeInputSecond.value);

                    if (timeCheckBox.checked === true) {
                        calculateBus(seconds);
                    } else {
                        calculateTime();

                    }
                    calculateTable();
                }

            }

            function calculateInterval(seconds) {
                if (busInput.value == 0) {
                    alert("ავტობუსების რაოდენობის ველში მითითებულია დაუშვებელი რიცხვი (0)");
                } else {
                    var result = seconds / busInput.value;
                    var date = new Date(0);
                    date.setSeconds(result);
                    var resultString = date.toISOString().substr(11, 8);
                    var splittedResult = resultString.split(":");
                    intervalInputHour.value = splittedResult[0];
                    intervalInputMinute.value = splittedResult[1];
                    intervalInputSecond.value = splittedResult[2];
                    var nashti = result % 3600 % 60 % 1;
                    if (nashti == 0) {
                        notes.innerHTML = "შედეგი ჯერადია. (ნაშთის გარეშეა, ანუ ნაშთი=0)";
                    } else {
                        var recalculatedSeconds = (splittedResult[0] * 3600) + (splittedResult[1] * 60) + parseInt(splittedResult[2]);
                        var recalculatedTime = recalculatedSeconds * busInput.value;
                        var recalculatedDate = new Date(0);
                        recalculatedDate.setSeconds(recalculatedTime);
                        var recalculatedTime = recalculatedDate.toISOString().substr(11, 8);
                        notes.innerHTML = "<label style='color:red;'>ყურადღება, შედეგი არ არის ჯერადი</label>"
                                + "<br><label>ნაშთი= " + nashti + "</label><br>"
                                + "<label>მიღებული ინტერვალისგან გადათვლილი ბრუნის დრო უდრის " + recalculatedTime + "</label>";
                    }
                }
            }

            function calculateBus(seconds) {
                var intervalSeconds = (intervalInputHour.value * 60 * 60) + (intervalInputMinute.value * 60) + parseInt(intervalInputSecond.value);
                if (intervalSeconds === 0) {
                    alert("მითითებული ინტერვალი დაუშვებელია (0). ინტერვალი უნდა იყოს არანაკლებ 1 წამი");
                } else {
                    if (intervalSeconds > seconds) {
                        alert("ინტერვალის დრო აღემათება ბრუნის დროს, რაც დაუშვებელია");
                    } else {
                        var result = seconds / intervalSeconds;
                        var nashti = result % 1;
                        busInput.value = parseInt(result);
                        if (nashti == 0) {
                            notes.innerHTML = "შედეგი ჯერადია. (ნაშთის გარეშეა, ანუ ნაშთი=0)";
                        } else {

                            notes.innerHTML = "<label style='color:red;'>ყურადღება, შედეგი არ არის ჯერადი, რაც დაუშვებელია(ავტობუსი ვერ გაიყოფა) </label>"
                                    + "<br><label>ნაშთი= " + nashti + "</label><br>"
                                    + "<label>ჯერადი შედეგის მისაღებად ან დააკელი ბრუნის დრო ან გაზარდე ინტერვალის დრო</label>";

                        }
                    }
                }
            }

            function calculateTime() {
                var seconds = (intervalInputHour.value * 60 * 60) + (intervalInputMinute.value * 60) + intervalInputSecond.value * 1;
                var result = seconds * busInput.value;
                console.log(result);
                var date = new Date(0);
                date.setSeconds(result);
                var resultString = date.toISOString().substr(11, 8);
                var splittedResult = resultString.split(":");
                timeInputHour.value = splittedResult[0];
                timeInputMinute.value = splittedResult[1];
                timeInputSecond.value = splittedResult[2];
                notes.innerHTML = "შედეგი ჯერადია. (ნაშთის გარეშეა, ანუ ნაშთი=0)";
            }


            function   timeInputHourPlusPlus() {
                var x = parseInt(timeInputHour.value) + 1
                if (x < 10) {
                    timeInputHour.value = "0" + x;
                } else {
                    timeInputHour.value = x;
                }
            }

            function   timeInputHourMinusMinus() {
                var x = parseInt(timeInputHour.value) - 1
                if (x < 0) {
                    x = 0;
                    timeInputMinute.value = 0;
                    alert("საათების 0 ზე ქვემოთ ჩამოსვლა დაუშვებელია");

                }
                if (x < 10) {
                    timeInputHour.value = "0" + x;
                } else {
                    timeInputHour.value = x;
                }
            }

            function   intervalInputHourPlusPlus() {
                var x = parseInt(intervalInputHour.value) + 1
                if (x < 10) {
                    intervalInputHour.value = "0" + x;
                } else {
                    intervalInputHour.value = x;
                }
            }

            function   intervalInputHourMinusMinus() {
                var x = parseInt(intervalInputHour.value) - 1
                if (x < 0) {
                    x = 0;
                    intervalInputMinute.value = 0;
                    alert("საათების 0 ზე ქვემოთ ჩამოსვლა დაუშვებელია");

                }
                if (x < 10) {
                    intervalInputHour.value = "0" + x;
                } else {
                    intervalInputHour.value = x;
                }
            }


            function calculateTable() {
                var timeSeconds = (timeInputHour.value * 60 * 60) + (timeInputMinute.value * 60) + parseInt(timeInputSecond.value);
                var plusMinus = plusMinusInput.value * 60;
                var startingTime = timeSeconds + plusMinus;
                var endingTime = timeSeconds - plusMinus;

                var tableZero = "<table><tr><th>შედეგის<br>ნომერი</th><th>ბრუნის დრო</th><th>ავტობუსების<br>რაოდენობა</th><th>ინტერვალი</th></tr>"

                var table = "<table><tr><th>შედეგის<br>ნომერი</th><th>ბრუნის დრო</th><th>ავტობუსების<br>რაოდენობა</th><th>ინტერვალი</th></tr>"

                var a = 0;
                for (x = startingTime; x > endingTime - 1; x--) {
                    if (x == 0) {
                        break;
                    }
                    var result = x / busInput.value;
                    var roundTime = new Date(0);
                    roundTime.setSeconds(x);
                    var roundTimeResultString = roundTime.toISOString().substr(11, 8);

                    var intervalTime = new Date(0);
                    intervalTime.setSeconds(result);
                    var intervalTimeResultString = intervalTime.toISOString().substr(11, 8);

                    var roundTimeSplittedResult = roundTimeResultString.split(":");
                    var roundTimeSeconds = roundTimeSplittedResult[2];
                    var interalSplittedResult = intervalTimeResultString.split(":");
                    var intervalSeconds = interalSplittedResult[2];


                    var nashti = result % 3600 % 60 % 1;
                    if (nashti == 0) {
                        table = table + "<tr><td>" + a + "</td><td>" + roundTimeResultString + "</td><td>" + busInput.value + "</td><td>" + intervalTimeResultString + "</td></tr>";
                        if (roundTimeSeconds == 0 && intervalSeconds == 00) {
                            tableZero = tableZero + "<tr><td>" + a + "</td><td>" + roundTimeResultString + "</td><td>" + busInput.value + "</td><td>" + intervalTimeResultString + "</td></tr>";

                        }



                        a++;
                    }

                }
                tableZero = tableZero + "</table>";
                table = table + "</table>";
                cxriliZero.innerHTML = tableZero;
                cxrili.innerHTML = table;
            }

            //-----------------------------------------
            function koko() {
                alert("koko");
            }
            var x = 0;
            function myFunction() {
                var h = document.getElementById("h");
                var m = document.getElementById("m");
                var s = document.getElementById("s");
                var b = document.getElementById("b");
                var r = document.getElementById("r");
                var n = document.getElementById("n");
                var g = document.getElementById("g");
                var d = document.getElementById("d");
                if (h.value < 0) {
                    alert("საათებში მითითებულია დაუშვებელი რიცხვი");
                    h.value = 0;
                }
                if (m.value < 0 || m.value > 59) {
                    alert("წუთებში მითითებულია დაუშვებელი რიცხვი");
                    m.value = 0;
                }
                if (s.value < 0 || s.value > 59) {
                    alert("წამებში მითითებულია დაუშვებელი რიცხვი");
                    s.value = 0;
                }
                if (b.value <= 0) {
                    alert("ავტობუსების რაოდენობად მითითებულია დაუშვებელი რიცხვი");
                    s.value = 1;
                }
                var seconds = (h.value * 60 * 60) + (m.value * 60) + parseInt(s.value);
                var result = seconds / b.value;
                var date = new Date(0);
                date.setSeconds(result);
                var re = date.toISOString().substr(11, 8);

                var na = result % 3600 % 60 % 1;

                r.innerHTML = re;
                n.innerHTML = na;


                var tt = re.split(":");
                var sec = tt[0] * 3600 + tt[1] * 60 + tt[2] * 1;
                var newRes = sec * b.value;
                var date = new Date(0);
                date.setSeconds(newRes);
                var newResult = date.toISOString().substr(11, 8);
                g.innerHTML = newResult;

                var diff = seconds - newRes;
                var differ = new Date(0);
                differ.setSeconds(diff);
                var differResult = differ.toISOString().substr(11, 8);
                d.innerHTML = differResult;

            }
        </script>
    </body>
</html>
