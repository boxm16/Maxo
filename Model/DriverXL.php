<?php

class DriverXL {

    private $number;
    private $name;
    function __construct($number, $name) {
        $this->number = $number;
        $this->name = $name;
    }

    function getNumber() {
        return $this->number;
    }

    function getName() {
        return $this->name;
    }

    function setNumber($number) {
        $this->number = $number;
    }

    function setName($name) {
        $this->name = $name;
    }



}
