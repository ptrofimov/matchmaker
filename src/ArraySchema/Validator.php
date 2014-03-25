<?php
namespace ArraySchema;

class Validator
{
    /** @var array */
    private $schema;
    /** @var bool */
    private $isValid;
    /** @var array */
    private $errors;

    public function __construct(array $schema)
    {
        $this->schema = $schema;
    }

    /** @return array */
    public function schema()
    {
        return $this->schema;
    }

    /** @return $this */
    public function validate(array $value)
    {
        $this->isValid = true;
        $this->errors = [];

        return $this;
    }

    /** @return bool */
    public function isValid()
    {
        if (is_null($this->isValid)) {
            throw new \LogicException('Not yet validated');
        }

        return $this->isValid;
    }

    /** @return array */
    public function errors()
    {
        if (is_null($this->errors)) {
            throw new \LogicException('Not yet validated');
        }

        return $this->errors;
    }
}
