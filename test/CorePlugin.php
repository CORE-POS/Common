<?php

class CorePlugin extends PHPUnit_Framework_TestCase
{
    public function testPlugin()
    {
        $plugin = new COREPOS\common\CorePlugin();

        $plugin->pluginEnable();
        $plugin->pluginDisable();
        $plugin->settingChange();
        $this->assertEquals(false, $plugin->pluginUrl());

        $dir = realpath(__DIR__ . '/../src');
        $this->assertEquals($dir, $plugin->pluginDir());
    }
}

