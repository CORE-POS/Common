<?php

class SQL extends PHPUnit_Framework_TestCase
{
    public function testSQL()
    {
        $dbc = new COREPOS\common\SQLManager('localhost', 'PDO_MYSQL', 'test', 'root', '');
        // this test is only going to work under CI
        if (!$dbc->isConnected()) {
            return;
        }
        $dbc->throwOnFailure(true);
        $this->assertEquals('test', $dbc->defaultDatabase());

        $this->assertEquals(false, $dbc->addConnection('localhost', 'PDO_MYSQL', 'test', 'notRoot', ''));
        $this->assertEquals(false, $dbc->addConnection('localhost', '', 'test', 'root', ''));
        $this->assertEquals(true, $dbc->addConnection('localhost', 'MYSQLI', 'test', 'root', ''));

        $this->assertEquals(true, $dbc->isConnected());
        $this->assertEquals(true, $dbc->isConnected('test'));
        $this->assertEquals(false, $dbc->isConnected('foo'));

        $this->assertEquals(true, $dbc->selectDB('test'));
        $this->assertEquals("'foo'", $dbc->escape('foo'));
        $this->assertEquals('CURDATE()', $dbc->curdate());
        $this->assertEquals('datediff(foo,bar)', $dbc->datediff('foo', 'bar'));
        $this->assertEquals("period_diff(date_format(foo, '%Y%m'), date_format(bar, '%Y%m'))", $dbc->monthdiff('foo', 'bar'));
        $this->assertEquals("DATE_FORMAT(FROM_DAYS(DATEDIFF(foo,bar)), '%Y')+0", $dbc->yeardiff('foo', 'bar'));
        $this->assertEquals('TIMESTAMPDIFF(SECOND,foo,bar)', $dbc->seconddiff('foo', 'bar'));
        $this->assertEquals('week(foo) - week(bar)', $dbc->weekdiff('foo', 'bar'));
        $this->assertEquals("DATE_FORMAT(foo,'%Y%m%d')", $dbc->dateymd('foo'));
        $this->assertEquals("CONVERT(foo,SIGNED)", $dbc->convert('foo', 'int'));
        $this->assertEquals("LOCATE(foo,f)", $dbc->locate('foo', 'f'));
        $this->assertEquals("CONCAT(foo,bar)", $dbc->concat('foo','bar','test'));

        $this->assertNotEquals('unknown', $dbc->connectionType());
        $this->assertEquals('unknown', $dbc->connectionType('foo'));

        $this->assertEquals(false, $dbc->setDefaultDB('foo'));
        $this->assertEquals(true, $dbc->setDefaultDB('test'));

        $res = $dbc->queryAll('SELECT 1 AS one');
        $this->assertNotEquals(false, $res);
        $this->assertEquals(1, $dbc->numRows($res));
        $this->assertEquals(1, $dbc->numFields($res));
        $this->assertEquals(false, $dbc->numRows(false));
        $this->assertEquals(true, $dbc->dataSeek($res, 0));

        $res = $dbc->query('SELECT ' . $dbc->curtime() . ' AS val');
        $this->assertNotEquals(false, $res);

        $dbc->startTransaction();
        $dbc->query('SELECT 1 AS one');
        $dbc->commitTransaction();
        $dbc->startTransaction();
        $dbc->query('SELECT 1 AS one');
        $dbc->rollbackTransaction();

        $query = 'SELECT * FROM mock';
        $arg_sets = array(array(), array(), array());
        $this->assertEquals(true, $dbc->executeAsTransaction($query, $arg_sets));

        $res = $dbc->query('SELECT ' . $dbc->week($dbc->now()) . ' AS val');
        $this->assertNotEquals(false, $res);

        $this->assertEquals(false, $dbc->tableDefinition('not_real_table'));
        $this->assertEquals(false, $dbc->detailedDefinition('not_real_table'));
        $this->assertEquals(false, $dbc->isView('not_real_table'));
        $this->assertEquals(false, $dbc->isView('mock'));
        $this->assertEquals(true, $dbc->isView('vmock'));
        $this->assertInternalType('string', $dbc->getViewDefinition('vmock'));
        $this->assertEquals(false, $dbc->getViewDefinition('mock'));

        $tables = $dbc->getTables();
        $this->assertInternalType('array', $tables);

        $this->assertEquals('test', $dbc->defaultDatabase());

        $prep = $dbc->prepare('SELECT 1 AS one');
        $this->assertEquals(1, $dbc->getValue($prep));
        $this->assertNotEquals(0, count($dbc->getRow($prep)));
        $this->assertNotEquals(0, count($dbc->matchingColumns('mock', 'mock')));

        $badDef = array('not'=>'real');
        $this->assertEquals(true, $dbc->cacheTableDefinition('mock', $badDef));
        $this->assertEquals($badDef, $dbc->tableDefinition('mock'));
        $this->assertEquals(true, $dbc->clearTableCache());
        $this->assertNotEquals($badDef, $dbc->tableDefinition('mock'));

        $this->assertNotEquals(false, $dbc->getMatchingColumns('mock', 'test', 'mock', 'test'));

        $this->assertNotEquals(false, $dbc->smartInsert('mock', array(
            'val' => 'row2',
            'nonColumn' => 'foo',
        )));
        $this->assertNotEquals(false, $dbc->smartUpdate('mock', array(
            'val' => 'row2',
            'nonColumn' => 'foo',
        ), 'id=2'));
        $this->assertEquals(true, $dbc->transfer('test', 'select val from mock', 'test', 'insert into mock (val)'));
        $dbc->query('TRUNCATE TABLE mock');

        $this->assertEquals(true, $dbc->close());
        $dbc->close('test', true);
    }



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

