<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once 'vendor/autoload.php';

$loader = new \Composer\Autoload\ClassLoader();

$actionClassName = 'app\\actions\\' . ucwords($_GET['action']);
$actionInstance = new $actionClassName();
$actionInstance->run();
