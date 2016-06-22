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

        $search = COREPOS\common\CorePlugin::pluginMap();
        $this->assertInternalType('array', $search);

        $this->assertEquals(false, COREPOS\common\CorePlugin::isEnabled('foo'));

        $s = DIRECTORY_SEPARATOR;
        $file = __DIR__ . "{$s}..{$s}src{$s}mvc{$s}ValueContainer.php";
        $this->assertEquals('mvc', (COREPOS\common\CorePlugin::memberOf($file, 'src')));
        $this->assertEquals(false, (COREPOS\common\CorePlugin::memberOf($file, 'plugins')));
    }
}

