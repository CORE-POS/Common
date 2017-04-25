<?php

class TestNamedSession extends PHPUnit_Framework_TestCase
{
    public function testNS()
    {
        $ns = new COREPOS\common\NamedSession('test');
        $this->assertEquals(false, isset($ns->val));
        $ns->val = 'foo';
        $this->assertEquals('foo', $ns->val);
        $this->assertEquals(true, isset($ns->val));
        unset($ns->val);
        $this->assertEquals(false, isset($ns->val));
    }
}

