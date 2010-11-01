<?php

namespace Whitewashing\DateTime;

class DateTime extends \DateTime
{
    /**
     * To prevent infinite recursions
     *
     * @var bool
     */
    private static $_immutable = true;

    public function add($interval)
    {
        if (self::$_immutable) {
            self::$_immutable = false;
            $newDate = clone $this;
            $newDate->add($interval);
            self::$_immutable = true;
            return $newDate;
        } else {
            return parent::add($interval);
        }
    }

    public function modify($modify)
    {
        if (self::$_immutable) {
            self::$_immutable = false;
            $newDate = clone $this;
            $newDate->modify($modify);
            self::$_immutable = true;
            return $newDate;
        } else {
            return parent::modify($modify);
        }
    }

    public function sub($interval)
    {
        if (self::$_immutable) {
            self::$_immutable = false;
            $newDate = clone $this;
            $newDate->sub($interval);
            self::$_immutable = true;
            return $newDate;
        } else {
            return parent::sub($interval);
        }
    }

    public function setDate($year, $month, $day) {
        throw new ImmutableException();
    }
    public function setISODate($year, $week, $day=null) {
        throw new ImmutableException();
    }
    public function setTime($hour, $minute, $second=null) {
        throw new ImmutableException();
    }
    public function setTimestamp($timestamp) {
        throw new ImmutableException();
    }
    public function setTimezone($timezone) {
        throw new ImmutableException();
    }
}