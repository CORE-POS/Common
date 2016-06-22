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
        ob_start();
        $this->assertEquals(0, $m->cli(3, array('BasicModel.php', '--new-view', 'FooView.php')));
        $this->assertEquals(true, file_exists('FooViewModel.php'));
        unlink('FooViewModel.php');
        ob_end_clean();
        chdir($here);

        $this->assertEquals('[]', $m->toJSON());
        $this->assertEquals('', $m->toOptions());
        $this->assertNotEquals(0, strlen($m->columnsDoc()));
        $this->assertEquals(array(), $m->getModels());
        $m->setConnectionByName('foo'); // interface method

        ob_start();
        $this->assertEquals(1, $m->cli(0, array()));
        $this->assertEquals(0, $m->cli(5, array(
            'BasicModel.php', 
            '--doc', 
            __DIR__ . '/MockModel.php',
            __DIR__ . '/noSuchFile',
            __DIR__ . '/../phpunit.xml'
        )));
        ob_get_clean();
    }

    public function testMock()
    {
        $dbc = new COREPOS\common\SQLManager('localhost', 'PDO_MYSQL', 'test', 'root', '');
        // this test is only going to work under CI
        if (!$dbc->isConnected()) {
            return;
        }
        if (!class_exists('MockModel')) {
            include(__DIR__ . '/MockModel.php');
        }
        $model = new MockModel($dbc);
        $this->assertEquals(0, $model->val());
        $this->assertEquals(null, $model->string());

        ob_start();
        $this->assertEquals(false, $model->normalize('test', 99));
        $this->assertEquals(999, $model->normalize('test'));
        $this->assertEquals(true, $model->normalize('test', COREPOS\common\BasicModel::NORMALIZE_MODE_APPLY));
        $this->assertEquals(0, $model->normalize('test'));
        ob_end_clean();

        $this->assertEquals(true, $model->whichDB('test'));
        $this->assertInternalType('array', $model->createIfNeeded('test'));

        $model = new MockModel($dbc);
        $model->string('mockString');
        $this->assertEquals(true, $model->save());
        $model->reset();
        $model->id(2);
        $this->assertEquals(false, $model->load());
        $model->id(1);
        $this->assertEquals(true, $model->load());
        $this->assertEquals('mockString', $model->string());
        $model->id(1);
        $model->string('newString');
        $this->assertEquals(true, $model->save());
        $model->reset();
        $model->id(1);
        $model->load();
        $this->assertEquals('newString', $model->string());
        $model->reset();
        $model->val(1, '>');
        $this->assertEquals(array(), $model->find());
        $model->reset();
        $model->string('newString');
        $this->assertEquals(1, count($model->find('id', true)));
        $model->id(1);
        $model->load();
        $obj = $model->toStdClass();
        $this->assertEquals(1, $obj->id);
        $model->reset();
        $this->assertNotEquals(false, strstr($model->toOptions(), '<option'));
        $this->assertNotEquals(false, strstr($model->toOptions(0, true), '<option'));
        $this->assertEquals(false, $model->delete());
        $model->id(1);
        $this->assertEquals(true, $model->delete());

        $this->assertInternalType('string', $model->doc());
        $this->assertInternalType('string', $model->columnsDoc());
    }
}

