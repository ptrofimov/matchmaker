<?php
namespace ArraySchema;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var array */
    private $schema;
    /** @var Validator */
    private $me;

    public function setUp()
    {
        $this->schema = ['key' => 'value'];
        $this->me = new Validator($this->schema);
    }

    public function testSchema()
    {
        $this->assertSame($this->schema, $this->me->schema());
    }

    public function testValidate()
    {
        $this->assertSame($this->me, $this->me->validate(['key' => 'value']));
        $this->assertTrue($this->me->isValid());
        $this->assertSame([], $this->me->errors());
    }

    /**
     * @expectedException \LogicException
     */
    public function testIsValidException()
    {
        $this->me->isValid();
    }

    /**
     * @expectedException \LogicException
     */
    public function testErrorsException()
    {
        $this->me->errors();
    }
}
