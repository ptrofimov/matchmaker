<?php

class ArrayValidTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptySchema()
    {
        $this->assertTrue(array_valid([], []));
    }
}
