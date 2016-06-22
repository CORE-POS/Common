<?php

if (!class_exists('MockConfig')) {
    include(__DIR__ . '/MockConfig.php');
}
if (!class_exists('MockSQL')) {
    include(__DIR__ . '/MockSQL.php');
}

function loadClass($class)
{
    if (substr($class, 0, 15) === 'COREPOS\\common\\') {
        $path = str_replace('\\', '/', substr($class, 15));
        $file = __DIR__ . '/../src/' . $path . '.php';
        if (file_exists($file)) {
            include($file);
        }
    }
}
spl_autoload_register('loadClass');

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    include(__DIR__ . '/../vendor/autoload.php');
}
