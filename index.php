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
                    <th colspan="2">ცვლადი პარამეტრები</th>
                    <th>შენიშვნები</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input id="timeCheckBox" type="checkbox" onclick="checkTimeInput()"></td>
                    <td >ბრუნის დრო</td><td><input id="timeInput" type="time" step="1" disabled="true" value="00:00:00" onchange="calculate"></td>
                    <td rowspan="3"><label id="notes"></label></td>
                </tr>
                <tr>
                    <td><input id="busCheckBox" type="checkbox" onclick="checkBusInput()"></td>
                    <td>ავტობუსების რაოდენობა</td><td><input id="busInput" type="number" disabled="true" ></td>
                </tr>
                <tr>
                    <td><input id="intervalCheckBox" type="checkbox" onclick="checkIntervalInput()"></td>
                    <td>ინტერვალი</td><td><input id="intervalInput" type="time" step="1" disabled="true" ></td>
                </tr>
            </tbody>

        </table>

        <hr>


        <label>ბრუნის დრო </label>
        <br>
    <lable>საათი</lable>&nbsp;&nbsp;&nbsp;<lable>წუთი</lable>&nbsp;&nbsp;&nbsp;<lable>წამი</lable>
    <br>
    <input id="h" type="number" max="24" min="0" value="00" onchange="myFunction()" onkeyup="myFunction()"><input id="m" type="number" max="59" min="0" value="00" onchange="myFunction()" onkeyup="myFunction()" ><input id="s" type="number" max="59" min="0" value="00" onchange="myFunction()" onkeyup="myFunction()" >
    <br>
    <br>
    <label>ავტობუსის რაოდენობა</label>
    <br>

    <input id="b" type="number" max="200" min="1" value="1" onchange="myFunction()" onkeyup="myFunction()" >
    <hr>
    <label>შედეგი:</label><label id="r"></label>
    <br>
    <br>
    <label>ნაშთი:</label><label id="n"></label> 

    <hr>
    <label><b>გამოთვლილი ბრუნის დრო  </b</label><label id="g"></label>
    <br>
    <br>
    <b><label>სხვაობა</label><label id="d"></label></b>
    <hr>

    <hr>
    <input step="1" type ="time" value="11:00:00">


    <script>
        var timeCheckBox = document.getElementById("timeCheckBox");
        var busCheckNox = document.getElementById("busCheckBox");
        var timeInput = document.getElementById("timeInput");
        var busInput = document.getElementById("busInput");
        var intervalInput = document.getElementById("intervalInput");
        var notes = document.getElementById("notes");

        function checkTimeInput() {
            if (timeCheckBox.checked === true) {
                if (canAddParameter()) {
                    timeInput.disabled = false;
                } else {
                    timeCheckBox.checked = false;
                    alert("სამივე პარამეტრის არჩევა დაუშვებელია");
                }
            } else {
                timeInput.disabled = true;
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
                    intervalInput.disabled = false;
                } else {
                    intervalCheckBox.checked = false;
                    alert("სამივე პარამეტრის არჩევა დაუშვებელია");
                }
            } else {
                intervalInput.disabled = true;
            }
        }

        function canAddParameter() {
            var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
            if (checkboxes.length < 3) {
                return true;
            } else {
                return false;
            }
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
