<?php

namespace DateHelper;

use DateTime;
use DateTimeZone;
use DateInterval;
use DatePeriod;

class DateHelper extends DateTime{

    const SUNDAY    = 0;
    const MONDAY    = 1;
    const TUESDAY   = 2;
    const WEDNESDAY = 3;
    const THURSDAY  = 4;
    const FRIDAY    = 5;
    const SATURDAY  = 6;

    protected $default_timezone = "Asia/Shanghai";

    public function __construct($time = 'now', $tz = null) {
        $timezone = $tz === null ? $this->default_timezone : $tz;
        parent::__construct($time, new DateTimeZone($timezone));
    }

    public static function now($tz = null) {
        return new static(null, $tz);
    }

    public function getYear() {
        return $this->format("Y");
    }

    public function getMonth() {
        return $this->format("m");
    }

    public function getDay() {
        return $this->format("d");
    }

    public function getHour() {
        return $this->format("H");
    }

    public function getMinute() {
        return $this->format("i");
    }

    public function getSecond() {
        return $this->format("s");
    }

    public function monday() {
        $dayMinus = $this->isSunday() ? 6 : $this->format("w") - 1;
        $dayMinus > 0 ? $this->subDay($dayMinus) : $this->addDay(abs($dayMinus));
        return $this;
    }

    public function nextMonday() {
        $this->sunday()->addDay();
        return $this;
    }

    public function isMonday() {
        return $this->format('w') == self::MONDAY;
    }

    public function tuesday() {
        $dayMinus = $this->isSunday() ? 5 : $this->format("w") - 2;
        $dayMinus > 0 ? $this->subDay($dayMinus) : $this->addDay(abs($dayMinus));
        return $this;
    }

    public function wednesday() {
        $dayMinus = $this->isSunday() ? 4 : $this->format("w") - 3;
        $dayMinus > 0 ? $this->subDay($dayMinus) : $this->addDay(abs($dayMinus));
        return $this;
    }

    public function thursday() {
        $dayMinus = $this->isSunday() ? 3 : $this->format("w") - 4;
        $dayMinus > 0 ? $this->subDay($dayMinus) : $this->addDay(abs($dayMinus));
        return $this;
    }

    public function friday() {
        $dayMinus = $this->isSunday() ? 2 : $this->format("w") - 5;
        $dayMinus > 0 ? $this->subDay($dayMinus) : $this->addDay(abs($dayMinus));
        return $this;
    }

    public function saturday() {
        $dayMinus = $this->isSunday() ? 1 : $this->format("w") - 6;
        $dayMinus > 0 ? $this->subDay($dayMinus) : $this->addDay(abs($dayMinus));
        return $this;
    }


    public function sunday() {
        $dayMinus = $this->isSunday() ? 0 : 7 - $this->format("w");
        $this->addDay($dayMinus);
        return $this;
    }

    public function nextSunday() {
        $this->sunday()->addDay(7);
        return $this;
    }

    public function isSunday() {
        return $this->format('w') == self::SUNDAY;
    }

    public function subDay($days = 1) {
        $this->sub(new DateInterval("P{$days}D"));
        return $this;
    }

    public function addDay($days = 1) {
        $this->add(new DateInterval("P{$days}D"));
        return $this;
    }

    public function addMonth($months = 1) {
        $this->add(new DateInterval("P{$months}M"));
        return $this;
    }

    public function subMonth($months = 1) {
        $this->sub(new DateInterval("P{$months}M"));
        return $this;
    }

    public function firstDayOfMonth() {
        $this->modify("first day of ".$this->format("Y-m-d"));
        return $this;
    }

    public function lastDayOfMonth() {
        $this->modify("last day of ".$this->format("Y-m-d"));
        return $this;
    }

    public function generateDateRange($startDate, $endDate, $type = 'day', $format = "Y-m-d"){
        if ($type == 'day') {
            return $this->generateDayRange($startDate, $endDate, $format);
        }
        if ($type == 'week') {
            return $this->generateWeekRange($startDate, $endDate, $format);
        }
        if ($type == 'month') {
            return $this->generateMonthRange($startDate, $endDate, $format);
        }

        return [];
    }

    public function generateDayRange($startDate, $endDate, $format = "Y-m-d") {
        $begin = new static($startDate);
        $end   = new static($endDate);

        $dateArr = [];
        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end->addDay());
        foreach($daterange as $date){
            $dateArr[] = $date->format($format);
        }
        return $dateArr;
    }

    public function generateWeekRange($startDate, $endDate, $format = "Y-m-d") {
        $begin    = new static($startDate);
        $end      = new static($endDate);
        $cloneEnd = clone $end;

        $dateArr = [];

        $t1 = clone $begin;
        $t2 = clone $begin;

        $t2->sunday();
        if ($t2 > $end) {
            $dateArr[] = [$t1->format($format), $end->format($format)];
            return $dateArr;
        }

        while($t2 < $end || $t1 <= $end){
            $dateArr[]=[$t1->format($format), $t2->format($format)];
            $t1->nextMonday();
            $t2->nextSunday();
            $t2 = $t2 > $end ? $cloneEnd : $t2;
        }

        return $dateArr;
    }

    public function generateMonthRange($startDate, $endDate, $format = "Y-m-d") {
        $begin    = new static($startDate);
        $end      = new static($endDate);
        $cloneEnd = clone $end;

        $dateArr = [];

        $t1 = clone $begin;
        $t2 = clone $begin;

        $t2->lastDayOfMonth();
        if ($t2 > $end) {
            $dateArr[] = [$t1->format($format), $end->format($format)];
            return $dateArr;
        }

        while($t2 < $end || $t1 <= $end){
            $dateArr[]=[$t1->format($format), $t2->format($format)];
            $t1->firstDayOfMonth()->addMonth();
            $t2->lastDayOfMonth()->addMonth();
            $t2 = $t2 > $end ? $cloneEnd : $t2;
        }

        return $dateArr;
    }

}