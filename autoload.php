<?php

if (!spl_autoload_functions()) {
    spl_autoload_register('register');
}

function register($class)
{
    $baseDir = PROJECT_ROOT . '/src/';
    $file = $baseDir . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
}
