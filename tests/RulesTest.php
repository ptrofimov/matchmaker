<?php
namespace matchmaker;

class RulesTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAllRules()
    {
        $this->assertInternalType('array', rules());
        $this->assertNotEmpty(rules());
    }

    public function testGetRule()
    {
        $this->assertTrue(is_callable(rules('any')));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetRuleException()
    {
        rules('not_found');
    }

    public function testAddNewRules()
    {
        $rules = rules(['number_five' => 5]);

        $this->assertInternalType('array', $rules);
        $this->assertArrayHasKey('number_five', $rules);
        $this->assertSame($rules, rules());
    }
}
