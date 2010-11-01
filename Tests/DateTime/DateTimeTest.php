<?php

namespace Whitewashing\Tests\DateTime;

use Whitewashing\DateTime\DateFactory,
    Whitewashing\DateTime\DateTime;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    public function testAddImmutable()
    {
        $dateInterval = new \DateInterval('P2D');
        $a = new DateTime("2009-12-12");
        $b = $a->add($dateInterval);
        $this->assertNotSame($a, $b);

        $this->assertEquals('2009-12-12', $a->format('Y-m-d'));
        $this->assertEquals('2009-12-14', $b->format('Y-m-d'));
    }

    public function testSubImmutable()
    {
        $dateInterval = new \DateInterval('P2D');
        $a = new DateTime("2009-12-12");
        $b = $a->sub($dateInterval);
        $this->assertNotSame($a, $b);

        $this->assertEquals('2009-12-12', $a->format('Y-m-d'));
        $this->assertEquals('2009-12-10', $b->format('Y-m-d'));
    }

    public function testModifyImmutable()
    {
        $a = new DateTime("2009-12-12");
        $b = $a->modify('+2 days');
        $this->assertNotSame($a, $b);

        $this->assertEquals('2009-12-12', $a->format('Y-m-d'));
        $this->assertEquals('2009-12-14', $b->format('Y-m-d'));
    }
}