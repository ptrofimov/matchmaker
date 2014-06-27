<?php
namespace matchmaker;

class MatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testMatchConstant()
    {
        $this->assertTrue(matcher(1, 1));
        $this->assertTrue(matcher('string', 'string'));
        $this->assertTrue(matcher(true, true));
        $this->assertFalse(matcher(1, 2));
        $this->assertFalse(matcher('string', 'other_string'));
        $this->assertFalse(matcher('string', 'other_string'));
    }

    public function testMatcher()
    {
        $this->assertTrue(matcher(1, ':integer'));
        $this->assertFalse(matcher('not_integer', ':integer'));
    }

    public function testMatcherMulti()
    {
        $this->assertTrue(matcher('1', ':string number'));
        $this->assertFalse(matcher('string', ':string number'));
    }

    public function testMatcherWithArgs()
    {
        $this->assertTrue(matcher(6, ':integer gt(5)'));
        $this->assertFalse(matcher(4, ':integer gt(5)'));
        $this->assertTrue(matcher(4, ':integer between(1,5)'));
        $this->assertFalse(matcher(7, ':integer between(1,5)'));
    }

    public function testMatchEmptyString()
    {
        $this->assertTrue(matcher('any_value', ''));
    }
}
