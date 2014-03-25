<?php
namespace ArraySchema\Matcher;

use ArraySchema\Matcher\Exception\TypeNotFoundException;

class Scalar
{
    /** @var array */
    private $types;
    private $typeChar;

    public function __construct(array $types, $typeChar)
    {
        $this->types = $types;
        $this->typeChar = (string) $typeChar;
    }

    /** @return array */
    public function types()
    {
        return $this->types;
    }

    public function typeChar()
    {
        return $this->typeChar;
    }

    public function matches($expected, $actual)
    {
        $validators = $this->types;

        if ($expected instanceof \Closure) {
            return $expected($actual);
        } elseif ($expected[0] == $this->typeChar) {
            $validator = substr($expected, 1);
            if (!isset($validators[$validator])) {
                throw new TypeNotFoundException("Validator $validator not found");
            }
            if (is_callable($validators[$validator])) {
                return $validators[$validator]($actual);
            } else {
                return $validators[$validator] == $actual;
            }
        }

        return str_replace('\\' . $this->typeChar, $this->typeChar, $expected) == $actual;
    }
}
