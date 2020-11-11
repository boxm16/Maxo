<?php

class BusDayTrip {

    private $busNumber;
    private $startTime;
    private $zeroATrip;
    private $zeroBTrip;
    private $aZeroTrip;
    private $bZeroTrip;
    private $halt;
    private $break;
    private $roundTrip;
    private $aTrip;
    private $bTrip;

    function getBusNumber() {
        return $this->busNumber;
    }

    function getStartTime() {
        return $this->startTime;
    }

    function getZeroATrip() {
        return $this->zeroATrip;
    }

    function getZeroBTrip() {
        return $this->zeroBTrip;
    }

    function getAZeroTrip() {
        return $this->aZeroTrip;
    }

    function getBZeroTrip() {
        return $this->bZeroTrip;
    }

    function getHalt() {
        return $this->halt;
    }

    function getBreak() {
        return $this->break;
    }

    function getRoundTrip() {
        return $this->roundTrip;
    }

    function getATrip() {
        return $this->aTrip;
    }

    function getBTrip() {
        return $this->bTrip;
    }

    function setBusNumber($busNumber) {
        $this->busNumber = $busNumber;
    }

    function setStartTime($startTime) {
        $this->startTime = $startTime;
    }

    function setZeroATrip($zeroATrip) {
        $this->zeroATrip = $zeroATrip;
    }

    function setZeroBTrip($zeroBTrip) {
        $this->zeroBTrip = $zeroBTrip;
    }

    function setAZeroTrip($aZeroTrip) {
        $this->aZeroTrip = $aZeroTrip;
    }

    function setBZeroTrip($bZeroTrip) {
        $this->bZeroTrip = $bZeroTrip;
    }

    function setHalt($halt) {
        $this->halt = $halt;
    }

    function setBreak($break) {
        $this->break = $break;
    }

    function setRoundTrip($roundTrip) {
        $this->roundTrip = $roundTrip;
    }

    function setATrip($aTrip) {
        $this->aTrip = $aTrip;
    }

    function setBTrip($bTrip) {
        $this->bTrip = $bTrip;
    }

}
