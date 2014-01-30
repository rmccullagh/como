<?php
/**
 * Copyright 2013 Ryan McCullagh
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

define('BASE_PATH', realpath(dirname(__FILE__)));
require __DIR__.'/bootstrap/autoload.php';


$logger     = \Cavalry\Log::getInstance();
$logger->push('__init__', "Initializing application");

$request    = new CLI\Request($argv);
$dispatcher = new CLI\Dispatcher($request);

$dispatcher->prepare();
$dispatcher->execute();


