<?php

class Trip {

    private $type;
    private $startTime;
    private $startTimeInSeconds;
    private $coverTime;

    function __construct($type, $startTime, $startTimeInSeconds, $coverTime) {
        $this->type = $type;
        $this->startTime = $startTime;
        $this->startTimeInSeconds = $startTimeInSeconds;
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
        if ($this->type == "halt") {
            return "black";
        }
        if ($this->type == "a") {
            return "blue";
        }
        if ($this->type == "b") {
            return "green";
        }
        if ($this->type == "break") {
            return "yellow";
        }
    }

    public function getInsideText() {
        if ($this->type == "a" || $this->type == "b") {
            return $this->startTimeInSeconds;
        }
        return "";
    }

    public function getStartTimeInSeconds() {
        if ($this->type == "a" | $this->type == "b" | $this->type == "break") {
            return $this->startTimeInSeconds;
        } else {
            return "";
        }
    }

    public function getEndTimeInSeconds() {
        if ($this->type == "a" | $this->type == "b" | $this->type == "break") {
            return $this->startTimeInSeconds+($this->coverTime*60);
        } else {
            return "";
        }
    }

}
