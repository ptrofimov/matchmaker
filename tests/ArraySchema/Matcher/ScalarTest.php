<?php
namespace ArraySchema\Matcher;

class ScalarTest extends \PHPUnit_Framework_TestCase
{
    /** @var array */
    private $types;
    private $typeChar;
    /** @var Scalar */
    private $me;

    public function setUp()
    {
        $this->types = [
            'numeric' => 'is_numeric',
            'lte5' => function ($value) {
                    return $value <= 5;
                },
            'notCallable' => 'value',
        ];
        $this->typeChar = '.';
        $this->me = new Scalar($this->types, $this->typeChar);
    }

    public function testPrimitiveValues()
    {
        $this->assertTrue($this->me->matches(1, 1));
        $this->assertFalse($this->me->matches(2, 1));
    }

    public function testReturnTypes()
    {
        $this->assertSame($this->types, $this->me->types());
    }

    public function testReturnTypeChar()
    {
        $this->assertSame($this->typeChar, $this->me->typeChar());
    }

    public function testValueClassMatch()
    {
        $this->assertTrue($this->me->matches($this->typeChar . 'numeric', 1));
        $this->assertFalse($this->me->matches($this->typeChar . 'numeric', 'string'));
    }

    public function testMatchWithCallback()
    {
        $callback = function ($value) {
            return is_numeric($value);
        };

        $this->assertTrue($this->me->matches($callback, 1));
        $this->assertFalse($this->me->matches($callback, 'string'));
    }

    /**
     * @expectedException \ArraySchema\Matcher\Exception\TypeNotFoundException
     */
    public function testTypeNotFoundException()
    {
        $this->me->matches($this->typeChar . 'notFound', 'value');
    }

    public function testNotCallableType()
    {
        $this->assertTrue($this->me->matches($this->typeChar . 'notCallable', 'value'));
        $this->assertFalse($this->me->matches($this->typeChar . 'notCallable', 'invalid'));
    }

    public function testEscapeSpecialChar()
    {
        $this->assertTrue($this->me->matches('\\' . $this->typeChar . 'notType', $this->typeChar . 'notType'));
        $this->assertFalse($this->me->matches('\\' . $this->typeChar . 'notType', 'notType'));
    }

    public function testMultiTypes()
    {
        $this->assertTrue($this->me->matches('.numeric.lte5', 4));
        $this->assertFalse($this->me->matches('.numeric.lte5', 6));
    }
}
