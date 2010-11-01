<?php

namespace Whitewashing\Tests\DateTime;

use Whitewashing\DateTime\DateFactory,
    Whitewashing\DateTime\DateTime;

class DateFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testSetGetTimeZone()
    {
        $tz = new \DateTimeZone("GMT");
        DateFactory::setDefaultTimeZone($tz);

        $this->assertSame($tz, DateFactory::getDefaultTimeZone());
    }

    public function testCreateDate()
    {
        $tz = new \DateTimeZone("GMT");
        DateFactory::setDefaultTimeZone($tz);

        $dateA = DateFactory::create(1, 1, 1, 1, 1, 2009);
        $dateB = DateFactory::create(1, 1, 1, 1, 1, 2009);

        $this->assertSame($dateA, $dateB);
    }

    public function testCreateDatePassTimeZone()
    {
        $tz = new \DateTimeZone("GMT");
        DateFactory::setDefaultTimeZone($tz);

        $dateA = DateFactory::create(1, 1, 1, 1, 1, 2009);

        $this->assertEquals($tz->getName(), $dateA->getTimezone()->getName());
    }

    public function testCreateDifferentDates()
    {
        $tz = new \DateTimeZone("GMT");
        DateFactory::setDefaultTimeZone($tz);

        $dateA = DateFactory::create(1, 1, 1, 1, 1, 2009);
        $dateB = DateFactory::create(1, 1, 1, 2, 1, 2009);

        $this->assertNotSame($dateA, $dateB);
        $this->assertEquals('2009-01-01 01:01:01', $dateA->format('Y-m-d H:i:s'));
        $this->assertEquals('2009-02-01 01:01:01', $dateB->format('Y-m-d H:i:s'));
    }

    public function testCreateMysqlDate()
    {
        $tz = new \DateTimeZone("GMT");
        DateFactory::setDefaultTimeZone($tz);

        $date = DateFactory::createFromMysqlDate('2009-01-01 10:10:10');
        $this->assertEquals('2009-01-01 10:10:10', $date->format('Y-m-d H:i:s'));
    }

    public function testTestingNow()
    {
        $date = new DateTime("now");
        DateFactory::setTestingNow($date);

        $this->assertSame($date, DateFactory::now());
    }
}