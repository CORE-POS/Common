<?php

class MVC extends PHPUnit_Framework_TestCase
{
    public function testContainers()
    {
        $v = new COREPOS\common\mvc\ValueContainer();
        $v->one = 1;
        $v->two = 2;
        $this->assertEquals(1, $v->one);
        $this->assertEquals(1, $v->tryGet('one'));
        $this->assertEquals(true, isset($v->two));
        $this->assertEquals(false, isset($v->three));

        $this->assertEquals(1, $v->current());
        $this->assertEquals('one', $v->key());
        $this->assertEquals(true, $v->valid());
        $v->next();
        $this->assertEquals(2, $v->current());
        $this->assertEquals('two', $v->key());
        $this->assertEquals(true, $v->valid());
        $v->next();
        $this->assertEquals(false, $v->valid());
        $v->rewind();
        $this->assertEquals(1, $v->current());
        $this->assertEquals('one', $v->key());
        $this->assertEquals(true, $v->valid());
        unset($v->one);
        $this->assertEquals(2, $v->current());
        $this->assertEquals('two', $v->key());
        $this->assertEquals(true, $v->valid());

        $this->assertEquals(7, $v->tryGet('seven', 7));
    }
}

