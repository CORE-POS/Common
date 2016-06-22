<?php

class FormLib extends PHPUnit_Framework_TestCase
{
    public function testLib()
    {
        $this->assertEquals('foo', COREPOS\common\FormLib::get('someVal', 'foo'));
    }
}

