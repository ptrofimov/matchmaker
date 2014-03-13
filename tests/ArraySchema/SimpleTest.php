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
            'one key' => [
                'schema' => [
                    'key' => 100.0,
                ],
                'examples' => [
                    [
                        'array' => [
                            'key' => 100.0,
                        ],
                        'isValid' => true,
                    ],
                    [
                        'array' => [
                            'key' => 200.0,
                        ],
                        'isValid' => false,
                    ],
                ],
            ],
        ];
    }

    /** @dataProvider dataProviderValidation */
    public function testSimpleSchema(array $schema, array $examples)
    {
        foreach ($examples as $example) {
            $this->assertSame($example['isValid'], validate($example['array'], $schema));
        }
    }
}
