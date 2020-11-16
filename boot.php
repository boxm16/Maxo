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
        <input type="number" onClick="this.select()" value="4" />

        <input type="number" onClick="this.select();" value="5" />

        <input type="number" id="text-box" size="20" value="3">
        <button onclick="selectText()">Select text</button>
    <script>
        function selectText() {
        const input = document.getElementById('text-box');
        input.focus();
        input.select();
        }
    </script>
</body>
</html>
