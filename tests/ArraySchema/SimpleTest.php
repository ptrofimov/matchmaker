<?php
namespace ArraySchema;

class SimpleTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyArray()
    {
        $this->assertTrue(validate([], []));
    }

    public function testSimpleSchema()
    {
        $this->assertTrue(validate(
            [
                'key' => 100.0,
            ],
            [
                'key' => 100.0,
            ]
        ));
        $this->assertFalse(validate(
            [
                'key' => 200.0,
            ],
            [
                'key' => 100.0,
            ]
        ));
    }
}
