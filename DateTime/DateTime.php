<?php
/*
 * Whitewashing
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@beberlei.de so I can send you a copy immediately.
 */

namespace Whitewashing\DateTime;

/**
 * Creates an immutable version of a DateTime object. It never changes the original, only returns new instances.
 *
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 */
class DateTime extends \DateTime
{
    /**
     * @param  string $interval
     * @return DateTime
     */
    public function add($interval)
    {
        $newDate = clone $this;
        return \date_add($newDate, $interval);
    }

    /**
     * @param  string $modify
     * @return DateTime
     */
    public function modify($modify)
    {
        $newDate = clone $this;
        return \date_modify($newDate, $modify);
    }

    /**
     * @param  string $interval
     * @return DateTime
     */
    public function sub($interval)
    {
        $newDate = clone $this;
        return \date_sub($newDate, $interval);
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