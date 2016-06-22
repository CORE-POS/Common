<?php

class BaseLogger extends PHPUnit_Framework_TestCase
{
    public function testLogger()
    {
        $bl = new COREPOS\common\BaseLogger();
        $this->assertEquals(false, $bl->verboseDebugging());
        $this->assertEquals('/dev/null', $bl->getLogLocation(0));
        $bl->log(0, ''); // interface method
        $bl->setRemoteSyslog('127.0.0.1');
    }
}
