<?php
namespace ArraySchema;

class SimpleTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyArray()
    {
        $this->assertTrue(validate([], []));
    }

    public function dataProviderValidation()
    {
        return [
            'simple key' => [
                'schema' => [
                    'key' => 100.0,
                ],
                'array' => [
                    'key' => 100.0,
                ],
                'isValid' => true,
            ],
            'invalid simple value' => [
                'schema' => [
                    'key' => 100.0,
                ],
                'array' => [
                    'key' => 200.0,
                ],
                'isValid' => false,
            ],
            'too many keys' => [
                'schema' => [
                    'key' => 100.0,
                ],
                'array' => [
                    'key' => 100.0,
                    'otherkey' => 100.0,
                ],
                'isValid' => false,
            ],
        ];
    }

    /** @dataProvider dataProviderValidation */
    public function testSimpleSchema(array $schema, array $array, $isValid)
    {
        $this->assertSame($isValid, validate($array, $schema));
    }
}
