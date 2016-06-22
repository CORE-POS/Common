<?php

class BasicModel extends PHPUnit_Framework_TestCase
{
    public function testModel()
    {
        $m = new COREPOS\common\BasicModel(null);
        $this->assertEquals(null, $m->db());
        $this->assertEquals('', $m->preferredDB());
        $m->setConfig(null);
        $m->setConnection(null);
        try {
            $m->foo();
        } catch (Exception $ex) {
            $this->assertEquals(true, $ex instanceof Exception);
        }
        $this->assertEquals(false, $m->load());
        $this->assertEquals(array(), $m->getColumns());
        $this->assertEquals(null, $m->getName());
        $this->assertEquals(null, $m->getFullQualifiedName());
        $m->setFindLimit(100);
        $this->assertEquals(false, $m->delete());

        ob_start();
        $this->assertEquals(false, $m->normalize('foo', 99));
        ob_get_clean();

        $here = getcwd();
        chdir(sys_get_temp_dir());
        $m->newModel('FooBarTestModel');
        $this->assertEquals(true, file_exists('FooBarTestModel.php'));
        include('FooBarTestModel.php');
        $this->assertEquals(true, class_exists('FooBarTestModel', false));
        unlink('FooBarTestModel.php');
        chdir($here);

        $this->assertEquals('[]', $m->toJSON());
        $this->assertEquals('', $m->toOptions());
        $this->assertNotEquals(0, strlen($m->columnsDoc()));
        $this->assertEquals(array(), $m->getModels());
        $m->setConnectionByName('foo'); // interface method

        ob_start();
        $this->assertEquals(1, $m->cli(0, array()));
        ob_get_clean();
    }
}

