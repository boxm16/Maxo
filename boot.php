<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bootstrap Example</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <style>
            input[type="number"] {
                width:45px;
            }

            .timeField{
                background-color: #218B3C ;
            }
            .timeFieldMinutes{

            }
        </style>

    </head>
    <body>

        <div class="container">
            <a href="index.php">ძველი ვერსია</a> <a href="lab.php">ვერსია 3</a>
            <div class="row">

                <div class="col-sm-5" style="background-color:lavender;">
                   
                    <table class="table" >

                            <tbody>
                                <tr>
                                    <td colspan="2"><label>+/-</label><input id="plusMinusInput" type="number" min="0" value="2" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)"></td>
                                    <td >
                                        <div >
                                            <button class="btn btn-success" type="button" id="backButton"  disabled="true" onclick="goBack()"><<<<</button>
                                            &nbsp;&nbsp;
                                            <button  class="btn btn-success" type="button" id="forwardButton"  disabled="true" onclick="goForward()">>>>></button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <input id="roundCheckBox" type="checkbox" onclick="checkCheckBoxes(event)">
                                    </td>
                                    <td>
                                        ბრუნის<br> დრო
                                    </td>


                                    <td>
                                        <table>
                                            <tr>
                                                <td >
                                                    საათი
                                                    <br>
                                                    <input id="roundInputHour" class="input" type="number" min="-1" disabled="true" value="02" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)">
                                                </td>
                                                <td >
                                                    წუთი
                                                    <br>
                                                    <input id="roundInputMinute" class="input" type="number" disabled="true" value="00" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)">
                                                </td>
                                                <td>
                                                    წამი
                                                    <br>
                                                    <input id="roundInputSecond" class="input" type="number" disabled="true" value="00" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    წუთები
                                                    <br>
                                                    <input type="number" style="width:90px" >
                                                </td>
                                                <td>
                                                    წამები
                                                    <br> 
                                                    <input type="number">
                                                </td> 
                                            </tr>
                                        </table>
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        <input id="busCheckBox" type="checkbox" onclick="checkCheckBoxes(event)" >
                                    </td>
                                    <td>
                                        ავტობუსების<br> რაოდენობა
                                    </td>
                                    <td colspan="3">
                                        <input id="busInput" class="input" type="number" disabled="true" style="width:135px;" max="200" min="1" value="1" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)">
                                    </td>


                                </tr>




                                <tr>
                                    <td>
                                        <input id="intervalCheckBox" type="checkbox" onclick="checkCheckBoxes(event)">
                                    </td>

                                    <td>
                                        ინტერვალი
                                    </td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td >
                                                    საათი
                                                    <br>
                                                    <input id="intervalInputHour" class="input" type="number" min="-1" disabled="true" value="02" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)">
                                                </td>
                                                <td >
                                                    წუთი
                                                    <br>
                                                    <input id="intervalInputMinute" class="input" type="number" disabled="true" value="00" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)">
                                                </td>
                                                <td>
                                                    წამი
                                                    <br>
                                                    <input id="intervalInputSecond" class="input" type="number" disabled="true" value="00" oninput="adjastTimeInputs(event)" onkeyup="incoming(event)">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>


                                </tr>
                                <tr><td colspan="5"></td></tr>
                                <tr><td colspan="5"><button type="button" class="btn  btn-primary" style="width:100%;" onclick="checkAndCalculate()"><b>გამოთვლა</b></button></td></tr>
                                <tr><td colspan="5"> 
                                        <label id="notes"></label>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
             



                <div class="col-sm-7" style="background-color:lavenderblush; height: 400px; overflow:auto"> 
                    <div >

                        <table class="table table-sm">
                            <tr>
                                <th>#</th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ავტობუსების რაოდენობა</b></h6></th><th><h6><b>ინტერვალი</b></h6></th>
                            </tr>
                            <tbody id="zeroTableBody">
                            </tbody>
                        </table>
                        <hr>
                        <div>
                            <label>ყველა შედეგები</label>
                            <table class="table table-sm">
                                <tr>
                                    <th>#</th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ავტობუსების რაოდენობა</b></h6></th><th><h6><b>ინტერვალი</b></h6></th>
                                </tr>




                                <tr>
                                    <th>#</th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ავტობუსების რაოდენობა</b></h6></th><th><h6><b>ინტერვალი</b></h6></th>
                                </tr>
                                <tr>
                                    <th>#</th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ავტობუსების რაოდენობა</b></h6></th><th><h6><b>ინტერვალი</b></h6></th>
                                </tr>
                                <tr>
                                    <th>#</th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ავტობუსების რაოდენობა</b></h6></th><th><h6><b>ინტერვალი</b></h6></th>
                                </tr>
                                <tr>
                                    <th>#</th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ავტობუსების რაოდენობა</b></h6></th><th><h6><b>ინტერვალი</b></h6></th>
                                </tr>
                                <tr>
                                    <th>#</th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ავტობუსების რაოდენობა</b></h6></th><th><h6><b>ინტერვალი</b></h6></th>
                                </tr>
                                <tr>
                                    <th>#</th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ავტობუსების რაოდენობა</b></h6></th><th><h6><b>ინტერვალი</b></h6></th>
                                </tr>
                                <tr>
                                    <th>#</th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ავტობუსების რაოდენობა</b></h6></th><th><h6><b>ინტერვალი</b></h6></th>
                                </tr>
                                <tr>
                                    <th>#</th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ავტობუსების რაოდენობა</b></h6></th><th><h6><b>ინტერვალი</b></h6></th>
                                </tr>
                                <tr>
                                    <th>#</th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ავტობუსების რაოდენობა</b></h6></th><th><h6><b>ინტერვალი</b></h6></th>
                                </tr>
                                <tr>
                                    <th>#</th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ბრუნის დრო</b></h6></th><th><h6><b>ავტობუსების რაოდენობა</b></h6></th><th><h6><b>ინტერვალი</b></h6></th>
                                </tr>
                                <tbody id="allTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
