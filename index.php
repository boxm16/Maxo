<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title></title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <style>
            input[type="number"] {
                width:50px;
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
        <div class="container">
            <a href="try.php">ახალი ვერსია</a> &nbsp; <a href="calculator.php">მანძილი/სიჩქარე/დრო</a> &nbsp;<a href="lab.php">MM</a>
            <div class="row">

                <div class="col-sm"> 
                    <br>
                    <table style="width:370px" >
                        <thead>
                            <tr>
                                <th>  <label>+/-</label><input id="plusMinusInput" type="number" min="0" value="2" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)"> </th>
                                <th colspan="4"> 
                                    <div class="text-center align-middle">
                                        <button class="btn btn-success" type="button" id="backButton"  disabled="true" onclick="goBack()"><<<<<<</button>
                                        &nbsp;&nbsp;
                                        <button  class="btn btn-success" type="button" id="forwardButton"  disabled="true" onclick="goForward()">>>>>>></button>
                                        <br>

                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr id="roundTr">
                                <td><input id="roundCheckBox" type="checkbox" onclick="checkCheckBoxes(event)"></td>
                                <td >ბრუნის დრო</td><td>საათი<br><input id="roundInputHour" class="input" type="number" min="-1" disabled="true" value="00" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)"></td><td>წუთი<br><input id="roundInputMinute" class="input" type="number" disabled="true" value="00" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)"></td><td>წამი<br><input id="roundInputSecond" class="input" type="number" disabled="true" value="00" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)"></td>

                            </tr>
                            <tr id="busTr">
                                <td><input id="busCheckBox" type="checkbox" onclick="checkCheckBoxes(event)" ></td>
                                <td>ავტობუსების<br> რაოდენობა</td><td colspan="3"><input id="busInput" class="input" type="number" disabled="true" style="width:125px;" max="200" min="1" value="0" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)"></td>
                            </tr>
                            <tr id="intervalTr">
                                <td><input id="intervalCheckBox" type="checkbox" onclick="checkCheckBoxes(event)"></td>
                                <td>ინტერვალი</td><td>საათი<br><input id="intervalInputHour" class="input" type="number" min="-1" disabled="true" value="00" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)"></td><td>წუთი<br><input id="intervalInputMinute" class="input" type="number" disabled="true" value="00" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)"></td><td>წამი<br><input id="intervalInputSecond" class="input" type="number" disabled="true"  value="00" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)"></td>
                            </tr>
                            <tr><td colspan="5"></td></tr>
                            <tr><td colspan="5"><button type="button" class="btn  btn-primary" style="width:100%;" onclick="checkAndCalculate()"><b>გამოთვლა</b></button></td></tr>
                            <tr><td colspan="5"> 
                                    <label id="notes"></label>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <hr>

                </div>
            </div>
            <div class="row">
                <div class="col col-sm">

                    <div>
                        <table>
                            <tr>
                                <th>#</th><th>ბრუნის დრო</th><th><h6><b>ავტობუსების<br>რაოდენობა</b></h6><th>ინტერვალი</th>
                            </tr>
                            <tbody id="zeroTableBody">
                            </tbody>
                        </table>
                        <hr>
                        <div>
                            <label>ყველა შედეგები</label>
                            <table>
                                <tr>
                                    <th>#</th><th>ბრუნის დრო</th><th><h6><b>ავტობუსების<br>რაოდენობა</b></h6></th><th>ინტერვალი</th>
                                </tr>
                                <tbody id="allTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var h = [];
            var hNumber = 0;

            function incoming(event) {
                zeroTableBody.innerHTML = "";
                allTableBody.innerHTML = "";
                if (event.keyCode === 13) {
                    checkAndCalculate();
                }
            }

            function checkCheckBoxes(event) {
                var target = event.target;

                zeroTableBody.innerHTML = "";
                allTableBody.innerHTML = "";
                var trElements = event.target.parentElement.parentElement;
                var trInputs = trElements.querySelectorAll(".input");
                if (selectedParametres() == 3) {
                    console.log("kkkk");
                    target.checked = false;
                    notes.style.color = "red";
                    notes.innerHTML = "სამივე პარამეტრის არჩევა დაუშვებელია";

                    //  redNotes.innerHTML = "სამივე პარამეტრის არჩევა დაუშვებელია";
                    for (i = 0; i < trInputs.length; i++) {
                        trInputs[i].disabled = true;
                    }
                } else {
                    notes.innerHTML = "";
                    if (target.checked == false) {
                        for (i = 0; i < trInputs.length; i++) {
                            trInputs[i].disabled = true;
                        }
                    } else {
                        for (i = 0; i < trInputs.length; i++) {
                            trInputs[i].disabled = false;
                        }
                    }
                }



            }

            function selectedParametres() {
                return document.querySelectorAll('input[type=checkbox]:checked').length;
            }



            function checkAndCalculate() {

                zeroTableBody.innerHTML = "";
                allTableBody.innerHTML = "";


                if (inputsValid()) {
                    saveInputs();
                    checkHistory();
                    calculate();
                }

            }

            function checkHistory() {
                if (h.length > 0 && hNumber > 0) {
                    backButton.disabled = false;
                }
                if (h.length > 0 && hNumber < h.length) {
                    forwardButton.disabled = false;
                }
                if (hNumber == 1) {
                    backButton.disabled = true;
                }
                if (hNumber == h.length) {
                    forwardButton.disabled = true;
                }

            }

            function saveInputs() {
                var inputs = [];
                inputs.push(roundCheckBox.checked);
                inputs.push(busCheckBox.checked);
                inputs.push(intervalCheckBox.checked);

                inputs.push(roundInputHour.value);
                inputs.push(roundInputMinute.value);
                inputs.push(roundInputSecond.value);

                inputs.push(busInput.value);

                inputs.push(intervalInputHour.value);
                inputs.push(intervalInputMinute.value);
                inputs.push(intervalInputSecond.value);

                inputs.push(plusMinusInput.value);

                h.push(inputs);
                hNumber = h.length;
            }

            function calculate() {
                var seconds = (roundInputHour.value * 60 * 60) + (roundInputMinute.value * 60) + parseInt(roundInputSecond.value);
                if (roundCheckBox.checked === true & busCheckBox.checked === true) {

                    calculateInterval(seconds);
                } else if (roundCheckBox.checked === true & intervalCheckBox.checked === true) {
                    calculateBus(seconds);
                } else if (busCheckBox.checked === true & intervalCheckBox.checked === true) {
                    calculateRound(seconds);
                }

                calculateTable();

            }
            function calculateRound(seconds) {
                var seconds = (intervalInputHour.value * 60 * 60) + (intervalInputMinute.value * 60) + intervalInputSecond.value * 1;
                var result = seconds * busInput.value;
                console.log(result);
                var date = new Date(0);
                date.setSeconds(result);
                var resultString = date.toISOString().substr(11, 8);
                var splittedResult = resultString.split(":");
                roundInputHour.value = splittedResult[0];
                roundInputMinute.value = splittedResult[1];
                roundInputSecond.value = splittedResult[2];
                notes.style.color = "green";
                notes.innerHTML = "პირდაპირი შედეგი ჯერადია.";
            }

            function calculateBus(seconds) {
                var intervalSeconds = (intervalInputHour.value * 60 * 60) + (intervalInputMinute.value * 60) + parseInt(intervalInputSecond.value);

                var result = seconds / intervalSeconds;
                var nashti = result % 1;
                busInput.value = parseInt(result);
                if (nashti == 0) {
                    notes.style.color = "green";
                    notes.innerHTML = "პირდაპირი შედეგი ჯერადია.";
                } else {

                    notes.innerHTML = "<label style='color:red;'>ყურადღება,პირდაპირი შედეგი არ არის ჯერადი </label>";
                    // + "<br><label>ნაშთი= " + nashti + "</label><br>"
                    //+ "<label>ჯერადი შედეგის მისაღებად ან დააკელი ბრუნის დრო ან გაზარდე ინტერვალის დრო</label>";

                }
            }

            function calculateInterval(seconds) {
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
                    notes.style.color = "green";
                    notes.innerHTML = "პირდაპირი შედეგი ჯერადია.";
                } else {
                    var recalculatedSeconds = (splittedResult[0] * 3600) + (splittedResult[1] * 60) + parseInt(splittedResult[2]);
                    var recalculatedTime = recalculatedSeconds * busInput.value;
                    var recalculatedDate = new Date(0);
                    recalculatedDate.setSeconds(recalculatedTime);
                    var recalculatedTime = recalculatedDate.toISOString().substr(11, 8);
                    notes.innerHTML = "<label style='color:red;'>ყურადღება, პირდაპირი შედეგი არ არის ჯერადი</label>";
                    //  + "<br><label>ნაშთი= " + nashti + "</label><br>"
                    // + "<label>მიღებული ინტერვალისგან გადათვლილი ბრუნის დრო უდრის " + recalculatedTime + "</label>";
                }
            }

            function calculateTable() {
                var roundSeconds = (roundInputHour.value * 60 * 60) + (roundInputMinute.value * 60) + parseInt(roundInputSecond.value);
                var plusMinus = plusMinusInput.value * 60;
                var startingTime = roundSeconds + plusMinus;
                var endingTime = roundSeconds - plusMinus;

                var zeroTableRows = "";
                var allTableRows = "";
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
                        allTableRows = allTableRows + "<tr><td>" + a + "</td><td>" + roundTimeResultString + "</td><td>" + busInput.value + "</td><td>" + intervalTimeResultString + "</td></tr>";
                        if (intervalSeconds == 00 || intervalSeconds == 30) {
                            zeroTableRows = zeroTableRows + "<tr><td>" + a + "</td><td>" + roundTimeResultString + "</td><td>" + busInput.value + "</td><td>" + intervalTimeResultString + "</td></tr>";

                        }
                        a++;
                    }

                }

                zeroTableBody.innerHTML = zeroTableRows;
                allTableBody.innerHTML = allTableRows;
            }

            function inputsValid() {
                if (roundCheckBox.checked & intervalCheckBox.checked) {
                    var seconds = (roundInputHour.value * 60 * 60) + (roundInputMinute.value * 60) + parseInt(roundInputSecond.value);
                    var intervalSeconds = (intervalInputHour.value * 60 * 60) + (intervalInputMinute.value * 60) + parseInt(intervalInputSecond.value);

                    if (intervalSeconds > seconds) {
                        notes.style.color = "red";
                        notes.innerHTML = "ინტერვალის დრო აღემათება ბრუნის დროს, რაც დაუშვებელია";
                        return false;
                        //    redNotes.innerHTML = "ინტერვალის დრო აღემათება ბრუნის დროს, რაც დაუშვებელია";
                    }
                }

                if (selectedParametres() < 2) {
                    notes.style.color = "red";
                    notes.innerHTML = "არჩეულია არასაკარისი პარამეტრები. საჭიროა 2 პარამეტრის არჩევა";
                    // redNotes.innerHTML = "არჩეულია არასაკარისი პარამეტრები. საჭიროა 2 პარამეტრის არჩევა";
                    return false;
                }
                if (intervalCheckBox.checked & intervalInputHour.value == 0 & intervalInputMinute.value == 0 & intervalInputSecond.value == 0) {
                    notes.style.color = "red";
                    notes.innerHTML = "მითითებული ინტერვალი დაუშვებელია (0). ინტერვალი უნდა იყოს არანაკლებ 1 წამი";
                    //  redNotes.innerHTML = "მითითებული ინტერვალი დაუშვებელია (0). ინტერვალი უნდა იყოს არანაკლებ 1 წამი";
                    return false;
                }
                if (busCheckBox.checked & busInput.value == 0) {
                    notes.style.color = "red";
                    notes.innerHTML = " ავტობუსების რაოდენობის ველში მითითებულია დაუშვებელი რიცხვი (0)";
                    // redNotes.innerHTML = " ავტობუსების რაოდენობის ველში მითითებულია დაუშვებელი რიცხვი (0)";
                    return false;
                }
                return true;
            }

            function adjastTimeInputs(event) {
                notes.innerHTML = "";
                zeroTableBody.innerHTML = "";
                allTableBody.innerHTML = "";
                var targetTR = event.target.parentNode.parentNode.id;
                var targetInputs;
                if (targetTR == "roundTr") {
                    targetInputs = "round"
                } else {
                    targetInputs = "interval";
                }
                var second = document.getElementById(targetInputs + "InputSecond");
                var minute = document.getElementById(targetInputs + "InputMinute");
                var hour = document.getElementById(targetInputs + "InputHour");

                if (second.value == 60) {
                    second.value = 0;
                    minute.value = parseInt(minute.value) + 1;
                }
                if (second.value == -1) {
                    minute.value = parseInt(minute.value) - 1;
                    if (hour.value == 0 && minute.value == -1) {
                        second.value = 0;
                    } else {
                        second.value = 59;
                    }
                }


                if (minute.value == 60) {
                    minute.value = 0;
                    timeInputHourPlusPlus(targetInputs);
                }
                if (minute.value == -1) {
                    minute.value = 59;
                    timeInputHourMinusMinus(targetInputs);
                }

                if (hour.value == -1) {
                    hour.value = 0;
                    notes.style.color = "red";
                    notes.innerHTML = "საათების 0 ზე ქვემოთ ჩამოსვლა დაუშვებელია";
                    //  redNotes.innerHTML = "საათების 0 ზე ქვემოთ ჩამოსვლა დაუშვებელია";

                }

            }

            function   timeInputHourPlusPlus(targetInputs) {
                var hour = document.getElementById(targetInputs + "InputHour");
                var x = parseInt(hour.value) + 1
                if (x < 10) {
                    hour.value = "0" + x;
                } else {
                    hour.value = x;
                }
            }

            function   timeInputHourMinusMinus(targetInputs) {
                var hour = document.getElementById(targetInputs + "InputHour");
                var minute = document.getElementById(targetInputs + "InputMinute");

                var x = parseInt(hour.value) - 1
                if (x < 0) {
                    x = 0;
                    hour.value = 0;
                    minute.value = 0;
                    notes.style.color = "red";
                    notes.innerHTML = "საათების 0 ზე ქვემოთ ჩამოსვლა დაუშვებელია";
                    //  redNotes.innerHTML = "საათების 0 ზე ქვემოთ ჩამოსვლა დაუშვებელია";
                }
                if (x < 10) {
                    hour.value = "0" + x;
                } else {
                    hour.value = x;
                }
            }

            function goBack() {
                hNumber--;
                var inputs = h[hNumber - 1];
                roundCheckBox.checked = inputs[0];
                busCheckBox.checked = inputs[1];
                intervalCheckBox.checked = inputs[2];

                checkCheckBoxHistory(roundCheckBox);
                checkCheckBoxHistory(busCheckBox);
                checkCheckBoxHistory(intervalCheckBox);

                roundInputHour.value = inputs[3];
                roundInputMinute.value = inputs[4];
                roundInputSecond.value = inputs[5];

                busInput.value = inputs[6];

                intervalInputHour.value = inputs[7];
                intervalInputMinute.value = inputs[8];
                intervalInputSecond.value = inputs[9];

                plusMinusInput.value = inputs[10];

                checkHistory();

                calculate();
            }

            function goForward() {
                hNumber++;
                var inputs = h[hNumber - 1];
                roundCheckBox.checked = inputs[0];
                busCheckBox.checked = inputs[1];
                intervalCheckBox.checked = inputs[2];

                checkCheckBoxHistory(roundCheckBox);
                checkCheckBoxHistory(busCheckBox);
                checkCheckBoxHistory(intervalCheckBox);


                roundInputHour.value = inputs[3];
                roundInputMinute.value = inputs[4];
                roundInputSecond.value = inputs[5];

                busInput.value = inputs[6];

                intervalInputHour.value = inputs[7];
                intervalInputMinute.value = inputs[8];
                intervalInputSecond.value = inputs[9];


                plusMinusInput.value = inputs[10];

                checkHistory();

                calculate();
            }

            function   checkCheckBoxHistory(checkBox) {
                var trElements = checkBox.parentElement.parentElement;
                var trInputs = trElements.querySelectorAll(".input");
                if (checkBox.checked == true) {
                    for (i = 0; i < trInputs.length; i++) {
                        trInputs[i].disabled = false;
                    }
                } else {
                    for (i = 0; i < trInputs.length; i++) {
                        trInputs[i].disabled = true;
                    }
                }
            }
        </script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    </body>

</html>
