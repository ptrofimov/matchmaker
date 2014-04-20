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

    public function testRangeQuantifier()
    {
        $this->true([1 => true], [':is_int{1}']);
        $this->false([1 => true, 2 => true], [':is_int{1}']);

        $this->true([1 => true, 2 => true], [':is_int{1,2}']);
        $this->false([1 => true, 2 => true, 3 => true], [':is_int{1,2}']);

        $this->true([1 => true, 2 => true], [':is_int{2,}']);
        $this->false([1 => true], [':is_int{2,}']);

        $this->true([1 => true, 2 => true], [':is_int{,2}']);
        $this->false([1 => true, 2 => true, 3 => true], [':is_int{,2}']);
    }

    public function testCallableFromDictionary()
    {
        $this->true([1 => true], [':int!']);
        $this->false(['key' => true], [':int!']);
    }

    public function testConstantFromDictionary()
    {
        $this->true(['hello' => true], [':hello!']);
        $this->false([1 => true], [':hello!']);
    }

    public function testInvalidMatcher()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->false(['hello' => true], [':invalid_matcher!']);
    }

    public function testInlineMatcher()
    {
        $this->true([5 => true], [':numberFive!', ':' => ['numberFive' => 5]]);
        $this->false([4 => true], [':numberFive!', ':' => ['numberFive' => 5]]);
    }

    public function testNestedKeys()
    {
        $this->true([5 => ['key' => 1]], ['*' => ['key']]);
        $this->false([5 => ['test' => 1]], ['!' => ['key']]);
        $this->false([5 => 1], ['!' => ['key']]);
    }

    public function testMatcherWithArguments()
    {
        $this->true([6 => null], [':gt(5)!']);
        $this->false([5 => null], [':gt(5)!']);
    }

    public function testMultipleMatchers()
    {
        $this->true([6 => null], [':gt(5) in(5,6,7)!']);
        $this->false([8 => null], [':gt(5) in(5,6,7)!']);
    }

    public function testErrors()
    {
        array_keys_valid(['key1' => null], ['key'], $errors);

        $this->assertEquals(
            [
                'key' => ['Key is required'],
            ],
            $errors
        );
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
