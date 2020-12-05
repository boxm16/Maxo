<?php

class TripPeriod {

    private $type;
    private $startPoint;
    private $startTimeInSeconds;
    private $length;

    function __construct($type, $startPoint, $startTimeInSeconds, $length) {
        $this->type = $type;
        $this->startPoint = $startPoint;
        $this->startTimeInSeconds = $startTimeInSeconds;
        $this->length = $length;
    }

    public function getStartPoint() {
        return $this->startPoint;
    }

    public function getLength() {
        return $this->length;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    function getPeriodColor() {
        if ($this->type == "halt") {
            return "black";
        }
        if ($this->type == "ab") {
            return "blue";
        }
        if ($this->type == "ba") {
            return "green";
        }
        if ($this->type == "break") {
            return "yellow";
        }
    }

    public function getStartTimeInSeconds() {
        if ($this->type == "ab" | $this->type == "ba" | $this->type == "break") {
            return $this->startTimeInSeconds;
        } else {
            return "";
        }
    }

    public function getEndTimeInSeconds() {
        if ($this->type == "ab" | $this->type == "ba" | $this->type == "break") {
            return $this->startTimeInSeconds + ($this->length * 60);
        } else {
            return "";
        }
    }

}
