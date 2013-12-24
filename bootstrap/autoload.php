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
$ENV = isset($_SERVER['APP_ENV']) ? $_SERVER['APP_ENV'] : NULL;
switch($ENV) {
	case 'DEV':
		define('ENVIROMENT', 'development');
		error_reporting(-1);
		break;
	default:
		define('ENVIROMENT', 'production');
	break;
}
require  __DIR__ .'/functions.php';
require  __DIR__.'/SplClassLoader.php';

SplClassLoader::autoRegister(NULL, BASE_PATH.'/lib');

