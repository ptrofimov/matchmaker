<?php
namespace matchmaker;

class MatchesTest extends \PHPUnit_Framework_TestCase
{
    public function testScalar()
    {
        $this->assertTrue(matches(1, ':integer'));
        $this->assertFalse(matches('string', ':integer'));
    }

    public function testArray()
    {
        $pattern = [
            '*' => [
                'id' => ':integer gt(0)',
                'title' => ':string contains(super)',
            ],
        ];

        $this->assertTrue(
            matches(
                [
                    [
                        'id' => 1,
                        'title' => 'super cool book'
                    ],
                    [
                        'id' => 2,
                        'title' => 'another super cool book'
                    ],
                ],
                $pattern
            )
        );
        $this->assertFalse(
            matches(
                [
                    [
                        'id' => 1,
                        'title' => 'just book'
                    ],
                ],
                $pattern
            )
        );
        $this->assertFalse(
            matches(
                null,
                $pattern
            )
        );
    }
}
