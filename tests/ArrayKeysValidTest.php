<?php

class ArrayKeysValidTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptySchema()
    {
        $this->assertTrue(array_keys_valid([], []));
    }

    public function testConstantMatcher()
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

    public function testCallableMatcher()
    {
        $this->assertTrue(
            array_keys_valid(
                ['string' => 1, 2 => 2],
                [':is_string', ':is_int']
            )
        );
    }

    public function testOptionalQuantifier()
    {
        $this->true(['key' => 1], ['key?']);
        $this->true([], ['key?']);
        $this->true([], [':is_int?']);
        $this->true([1], [':is_int?']);
        $this->false([1, 2], [':is_int?']);
    }

    public function testMultiQuantifier()
    {
        $this->true(['key' => 1], ['key*']);
        $this->true([], ['key*']);
        $this->true([], [':is_int*']);
        $this->true([1], [':is_int*']);
        $this->true([1, 2], [':is_int*']);
        $this->true([1, 2, 3], [':is_int']);
//        $this->true([1, 2, 3], ['']);
        $this->true([1, 2, 3], ['*']);
    }

    public function testSingleQuantifier()
    {
        $this->true(['key' => 1], ['key!']);
        $this->true([1 => true], [':is_int!']);
        $this->false([1 => true, 2 => true], [':is_int!']);
    }

    private function true(array $array, array $schema)
    {
        $this->assertTrue(array_keys_valid($array, $schema));
    }

    private function false(array $array, array $schema)
    {
        $this->assertFalse(array_keys_valid($array, $schema));
    }
}
