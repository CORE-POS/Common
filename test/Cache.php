<?php

class Cache extends PHPUnit_Framework_TestCase
{
    public function testCache()
    {
        $classes = array(
            'COREPOS\\common\\cache\\file\\CacheItemPool',
        );
        foreach ($classes as $c) {
            $pool = new $c('test.cache');
            $pool->clear();

            $item = $pool->getItem('foo');
            $this->assertEquals('foo', $item->getKey());
            $this->assertEquals(null, $item->get());
            $this->assertEquals(false, $item->isHit());

            $item->set('bar');
            $item->expiresAt(new DateTime());
            $item->expiresAt(null);
            $item->expiresAfter(0);
            $item->expiresAfter(null);
            $item->expiresAfter(new DateInterval('P1D'));
            $this->assertEquals(true, $pool->save($item));

            $item = $pool->getItem('foo');
            $this->assertEquals('foo', $item->getKey());
            $this->assertEquals('bar', $item->get());
            $this->assertEquals(true, $item->isHit());

            $items = $pool->getItems(array('foo'));
            $this->assertEquals($item, $items[0]);

            $this->assertEquals(true, $pool->hasItem('foo'));
            $this->assertEquals(true, $pool->deleteItem('foo'));
            $this->assertEquals(false, $pool->hasItem('foo'));

            $baz = $pool->getItem('baz');
            $baz->set('deferred');
            $this->assertEquals(true, $pool->saveDeferred($baz));
            $this->assertEquals(true, $pool->commit());
        }
    }
}

