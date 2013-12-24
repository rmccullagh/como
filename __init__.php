<?php
define('BASE_PATH', realpath(dirname(__FILE__)));
require __DIR__.'/bootstrap/autoload.php';

$request 		= new CLI\Request($argv);
$dispatcher = new CLI\Dispatcher($request);

$dispatcher->prepare();
$dispatcher->execute();


