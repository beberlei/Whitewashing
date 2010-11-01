<?php

namespace Whitewashing\Tests\Util;

use Whitewashing\Util\String;

class StringTest extends \PHPUnit_Framework_TestCase
{
    static public function dataSlugize()
    {
        return array(
            array('FOOBAR', 'foobar'),
            array('FOO BAR', 'foo-bar'),
            array('Füo Bär', 'fo-br'),
            array('Foo    Bar', 'foo-bar'),
            array('l33t', 'l33t'),
            array(str_repeat('a', 100), str_repeat('a', 64))
        );
    }

    /**
     * @dataProvider dataSlugize
     * @param string $text
     * @param string $expectedSlug
     */
    public function testSlugize($text, $expectedSlug)
    {
        $this->assertEquals($expectedSlug, String::slugize($text));
    }
}