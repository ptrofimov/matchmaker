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
        $fixtures = [
            'keys',
        ];
        $data = [];
        foreach ($fixtures as $name) {
            foreach (require_once(dirname(__DIR__) . "/fixtures/keys.php") as $key => $value) {
                $data["$name: $key"] = $value;
            }
        }

        return $data;
    }

    /** @dataProvider dataProviderValidation */
    public function testSimpleSchema(array $schema, array $array, $isValid)
    {
        $this->assertSame($isValid, validate($array, $schema));
    }
}
