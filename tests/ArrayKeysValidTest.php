<?php

class ArrayKeysValidTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptySchema()
    {
        $this->assertTrue(array_keys_valid([], []));
    }

    public function testSimple()
    {
        $this->assertTrue(
            array_keys_valid(
                ['key1' => 1, 'key2' => 2, 'key3' => 3],
                ['key1', 'key2', 'key3']
            )
        );
        $this->assertTrue(
            array_keys_valid(
                ['key1' => 1, 'key2' => 2, 'key3' => 3],
                ['key2', 'key1', 'key3']
            )
        );
        $this->assertFalse(
            array_keys_valid(
                ['key1' => 1, 'key2' => 2],
                ['key2', 'key1', 'key3']
            )
        );
    }
}
