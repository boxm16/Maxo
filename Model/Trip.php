<?php

class Trip {

    private $type;
    private $startTime;
    private $coverTime;

    function __construct($type, $startTime, $coverTime) {
        $this->type = $type;
        $this->startTime = $startTime;
        $this->coverTime = $coverTime;
    }

    function getType() {
        return $this->type;
    }

    function getStartTime() {
        return $this->startTime;
    }

    function getCoverTime() {
        return $this->coverTime;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setStartTime($startTime) {
        $this->startTime = $startTime;
    }

    function setCoverTime($coverTime) {
        $this->coverTime = $coverTime;
    }

    function getTripColor() {
        if($this->type=="halt"){
            return "black";
        }
        if($this->type=="a"){
            return "blue";
        }
        if($this->type=="b"){
            return "green";
        }
    
        
    }

}
