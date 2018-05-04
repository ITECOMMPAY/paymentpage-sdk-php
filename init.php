<?php

define('PROJECT_ROOT', __DIR__);
define('CONFIG_PATH', PROJECT_ROOT . '/config/config.php');
define('LOG_PATH', PROJECT_ROOT . '/log/');
require 'autoload.php';
set_exception_handler(array('System\ExceptionHandler', 'handle'));
