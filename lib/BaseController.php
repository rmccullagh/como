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
use \ModelFactory;
use \Config\Xml;
abstract class BaseController {
	public function __construct() {
		$this->modelFactory = new ModelFactory();		
		$this->config				= Xml::getInstance();
		$model = str_replace(
			'Controller', 
			'Model', 
			get_class($this)
		); 

		if(file_exists(BASE_PATH.'/model/'.$model . '.php')) {
			$this->model_factory->load(
				str_replace(
					'Controller', 
					'', 
					get_class($this)
				)
			);
		} else {
			$this->model = NULL;
		}
	} 
}
