<?php

class MockSQL
{
    private static $rows = array();

    public static function clear()
    {
        self::$rows = array();
    }

    public static function addResult($row)
    {
        self::$rows[] = $row;
    }

    public function selectDB($db)
    {
        return true;
    }

    public function tableExists($table)
    {
        return true;
    }

    public function tableDefinition($table)
    {
        return array();
    }

    public function query($q)
    {
        return true;
    }

    public function prepare($q)
    {
        return true;
    }

    public function execute($q)
    {
        return true;
    }

    public function fetchRow($r)
    {
        if (count(self::$rows) === 0) {
            return false;
        }
        return array_shift(self::$rows);
    }

    public function numRows($r)
    {
        return count(self::$rows);
    }
}

