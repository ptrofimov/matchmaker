<?php
namespace matchmaker;

class KeyMatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf('Closure', key_matcher([]));
    }

    public function testKeyMatcher()
    {
        $keyMatcher = key_matcher([
            'number' => ':number',
            'string' => ':string',
        ]);

        $this->assertTrue($keyMatcher('number', 1));
        $this->assertFalse($keyMatcher('string', 1));
        $this->assertTrue($keyMatcher('other', 1));
        $this->assertFalse($keyMatcher());

        $this->assertTrue($keyMatcher('string', 'some string'));
        $this->assertTrue($keyMatcher());
    }

    public function testQuantifiers()
    {
        $keyMatcher = key_matcher(['key' => ':number']);
        $this->assertFalse($keyMatcher());
        $this->assertTrue($keyMatcher('key', 1));
        $this->assertTrue($keyMatcher());
        $this->assertTrue($keyMatcher('key', 1));
        $this->assertFalse($keyMatcher());

        $keyMatcher = key_matcher(['key!' => ':number']);
        $this->assertFalse($keyMatcher());
        $this->assertTrue($keyMatcher('key', 1));
        $this->assertTrue($keyMatcher());
        $this->assertTrue($keyMatcher('key', 1));
        $this->assertFalse($keyMatcher());

        $keyMatcher = key_matcher(['key?' => ':number']);
        $this->assertTrue($keyMatcher());
        $this->assertTrue($keyMatcher('key', 1));
        $this->assertTrue($keyMatcher());
        $this->assertTrue($keyMatcher('key', 1));
        $this->assertFalse($keyMatcher());

        $keyMatcher = key_matcher(['key*' => ':number']);
        $this->assertTrue($keyMatcher());
        $this->assertTrue($keyMatcher('key', 1));
        $this->assertTrue($keyMatcher());
        $this->assertTrue($keyMatcher('key', 1));
        $this->assertTrue($keyMatcher());

        $keyMatcher = key_matcher(['key{2}' => ':number']);
        $this->assertFalse($keyMatcher());
        $this->assertTrue($keyMatcher('key', 1));
        $this->assertFalse($keyMatcher());
        $this->assertTrue($keyMatcher('key', 1));
        $this->assertTrue($keyMatcher());

        $keyMatcher = key_matcher(['key{1,2}' => ':number']);
        $this->assertFalse($keyMatcher());
        $this->assertTrue($keyMatcher('key', 1));
        $this->assertTrue($keyMatcher());
        $this->assertTrue($keyMatcher('key', 1));
        $this->assertTrue($keyMatcher());
        $this->assertTrue($keyMatcher('key', 1));
        $this->assertFalse($keyMatcher());

        $keyMatcher = key_matcher([':string' => ':number']);
        $this->assertTrue($keyMatcher());
        $this->assertTrue($keyMatcher('key', 1));
        $this->assertTrue($keyMatcher());
        $this->assertTrue($keyMatcher('key', 1));
        $this->assertTrue($keyMatcher());
    }

    public function testNested()
    {
        $keyMatcher = key_matcher([
            'article' => [
                'id' => ':number',
                'title' => ':string',
            ],
        ]);

        $this->assertFalse($keyMatcher());
        $this->assertFalse($keyMatcher('article', 1));
        $this->assertFalse($keyMatcher('article', []));
        $this->assertFalse($keyMatcher('article', ['id' => 1, 'title' => 1]));
        $this->assertTrue($keyMatcher('article', ['id' => 1, 'title' => 'some title']));
        $this->assertTrue($keyMatcher());
    }
}
