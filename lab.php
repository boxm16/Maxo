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
                    <td >ბრუნის დრო</td><td>საათი<br><input id="timeInputHour" type="number" disabled="true" max="24" min="0" value="02"  oninput="calculateByTime()"></td><td>წუთი<br><input id="timeInputMinute" type="number" disabled="true" max="59" min="0" value="00" onchange="calculateByTime()" onkeyup="calculateByTime()"></td><td>წამი<br><input id="timeInputSecond" type="number" disabled="true" max="59" min="0" value="00" onchange="calculateByTime()" onkeyup="calculateByTime()"></td>

                </tr>
                <tr>
                    <td><input id="busCheckBox" type="checkbox" onclick="checkBusInput()"></td>
                    <td>ავტობუსების<br> რაოდენობა</td><td colspan="3"><input id="busInput" type="number" disabled="true" style="width:125px;" max="200" min="1" value="1" onchange="calculateByBus()" onkeyup="calculateByBus()"></td>
                </tr>
                <tr>
                    <td><input id="intervalCheckBox" type="checkbox" onclick="checkIntervalInput()"></td>
                    <td>ინტერვალი</td><td>საათი<br><input id="intervalInputHour" type="number" disabled="true" max="24" min="0" value="00" onchange="calculateByInterval()" onkeyup="calculateByInterval()"></td><td>წუთი<br><input id="intervalInputMinute" type="number" disabled="true" max="59" min="0" value="10" onchange="calculateByInterval()" onkeyup="calculateByInterval()"></td><td>წამი<br><input id="intervalInputSecond" type="number" disabled="true" max="59" min="0" value="00" onchange="calculateByInterval()" onkeyup="calculateByInterval()"></td>
                </tr>
            </tbody>
        </table>
        <label id="notes"></label>
        <hr>
        <p>Write something in the text field to trigger a function.</p>

        <input type="number" id="myInput" max="60" oninput="myFunction()">

        <p id="demo"></p>
        <hr>

        <script>


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
                }

            }
            function calculateByInterval() {
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




            //-----------------------------------------
            function myFunction() {
                var x = document.getElementById("myInput").value;
                if (x ==60) {
                    document.getElementById("myInput").value = 0;
                    x=0;
                }
                if (x ==-1) {
                    document.getElementById("myInput").value = 59;
                    x=59;
                }
                document.getElementById("demo").innerHTML = "You wrote: " + x;
            }
        </script>
    </body>
</html>
