<?php

spl_autoload_register(function ($class) {

    $baseDir = dirname(__DIR__) . '/';

    // convert namespace to file path
    $class = str_replace('\\', '/', $class);

    $file = $baseDir . $class . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});