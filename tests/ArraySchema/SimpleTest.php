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
        return require_once(dirname(__DIR__) . '/fixtures/keys.php');
    }

    /** @dataProvider dataProviderValidation */
    public function testSimpleSchema(array $schema, array $array, $isValid)
    {
        $this->assertSame($isValid, validate($array, $schema));
    }
}
