<?php

namespace Whitewashing\DateTime;

class DateFactory
{
    static private $_dates = array();

    static private $_timeZone = null;

    /**
     * @var DateTime
     */
    static private $_now = null;

    /**
     * @return DateTime
     */
    static public function now()
    {
        if (self::$_now == null) {
            self::$_now = new DateTime("now", DateFactory::getDefaultTimeZone());
        }
        return self::$_now;
    }

    /**
     * @param DateTime $now
     */
    static public function setTestingNow(DateTime $now)
    {
        self::$_now = $now;
    }

    /**
     * @param DateTimeZone $timeZone
     */
    static public function setDefaultTimeZone(\DateTimeZone $timeZone)
    {
        self::$_timeZone = $timeZone;
    }

    /**
     * @return DateTimeZone
     */
    static public function getDefaultTimeZone()
    {
        if (self::$_timeZone == null) {
            self::$_timeZone = new \DateTimeZone(date_default_timezone_get());
        }
        return self::$_timeZone;
    }

    /**
     * @param int $hour
     * @param int $minute
     * @param int $second
     * @param int $month
     * @param int $day
     * @param int $year
     * @return DateTime
     */
    static public function create($hour, $minute, $second, $month, $day, $year)
    {
        $date = sprintf('%04d-%02d-%02d %02d:%02d:%02d', $year, $month, $day, $hour, $minute, $second);
        if (!isset(self::$_dates[$date])) {
            self::$_dates[$date] = new DateTime($date, self::getDefaultTimeZone());
        }
        return self::$_dates[$date];
    }

    /**
     * Create from Mysql Date
     * 
     * @param  string $mysqlDate
     * @return DateTime
     */
    static public function createFromMysqlDate($mysqlDate)
    {
        list($date, $time) = explode(" ", $mysqlDate);
        if($time == null) {
            $hour = $minute = $second = 0;
        } else {
            list($hour, $minute, $second) = explode(":", $time);
        }
        list($year, $month, $day) = explode("-", $date);
        return self::create($hour, $minute, $second, $month, $day, $year);
    }
}