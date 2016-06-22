<?php

class SQL extends PHPUnit_Framework_TestCase
{
    public function testSqlLib()
    {
        $this->assertInternalType('array', COREPOS\common\sql\Lib::getDrivers());
    }

    public function testAdapters()
    {
        $adapters = array('Mssql', 'Mysql', 'Pgsql', 'Sqlite');
        foreach ($adapters as $adapter) {
            $class = 'COREPOS\\common\\sql\\' . $adapter . 'Adapter';
            $obj = new $class();
            $this->assertInternalType('string', $obj->identifierEscape('foo'));
            $this->assertInternalType('string', $obj->defaultDatabase());
            $this->assertInternalType('string', $obj->temporaryTable('foo','bar'));
            $this->assertInternalType('string', $obj->sep());
            $this->assertInternalType('string', $obj->addSelectLimit('SELECT * FROM table', 5));
            $this->assertInternalType('string', $obj->currency());
            $this->assertInternalType('string', $obj->curtime());
            $this->assertInternalType('string', $obj->datediff('date1', 'date2'));
            $this->assertInternalType('string', $obj->monthdiff('date1', 'date2'));
            $this->assertInternalType('string', $obj->yeardiff('date1', 'date2'));
            $this->assertInternalType('string', $obj->weekdiff('date1', 'date2'));
            $this->assertInternalType('string', $obj->seconddiff('date1', 'date2'));
            $this->assertInternalType('string', $obj->dateymd('date1'));
            $this->assertInternalType('string', $obj->dayofweek('date1'));
            $this->assertInternalType('string', $obj->convert('date1','int'));
            $this->assertInternalType('string', $obj->locate('date1','te'));
            $this->assertInternalType('string', $obj->concat(array('1','2','3')));
        }
    }
}

