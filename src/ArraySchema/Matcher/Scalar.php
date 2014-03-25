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
        if ($expected instanceof \Closure) {
            return $expected($actual);
        } elseif ($types = $this->getTypes($expected)) {
            foreach ($types as $type) {
                if (!isset($this->types[$type])) {
                    throw new TypeNotFoundException("Type $type not found");
                }
                if (is_callable($this->types[$type])) {
                    if (!$this->types[$type]($actual)) {
                        return false;
                    }
                } else {
                    if ($this->types[$type] != $actual) {
                        return false;
                    }
                }
            }
            return true;
        }

        return str_replace('\\' . $this->typeChar, $this->typeChar, $expected) == $actual;
    }

    private function getTypes($string)
    {
        $string = str_replace(' ', '', trim($string));
        if ($string && $string[0] != $this->typeChar) {
            return [];
        }
        $string = str_replace('\\' . $this->typeChar, '__TYPE__', $string);
        $items = explode($this->typeChar, $string);
        array_shift($items);
        foreach ($items as &$item) {
            $item = str_replace('__TYPE__', '\\' . $this->typeChar, $item);
        }
        unset($item);

        return $items;
    }
}
