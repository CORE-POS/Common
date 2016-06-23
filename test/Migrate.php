<?php

class Migrate extends PHPUnit_Framework_TestCase
{
    function testMigrate()
    {
        $dbc = new COREPOS\common\SQLManager('localhost', 'PDO_MYSQL', 'test', 'root', '');
        if (!class_exists('MockModel2')) {
            include(__DIR__ . '/MockModel2.php');
        }
        $m = new MockModel2($dbc); 
        $m->normalize('test');
        $m->normalize('test', COREPOS\common\BasicModel::NORMALIZE_APPLY);
        $m->normalize('test');
        $m->normalize('test', COREPOS\common\BasicModel::NORMALIZE_APPLY);
    }
}

