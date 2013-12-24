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
class ModelFactory extends BaseModel implements ArrayAccess {
	public function __construct() {
		parent::__construct();
	}
	public function load($file)
	{
		$classPath = explode('_', $file);
		$classPath = array_map("ucfirst", $classPath);
		$classPath = implode('/', $classPath).'Model';
		$className = explode('/', $classPath);
		if(count($className) > 1)
			$class = $className[1];
	  else
			$class = $className[0];
   
		if(!isset($this->{$class})) {
			if(file_exists(BASE_PATH . '/model/' . $classPath . '.php')) {
				include BASE_PATH . '/model/' . $classPath . '.php';
				$this->{$class} = new $class();
				return $this->{$class};
			}	else {
	    	throw new \Exception($classPath . " was not found");
			}
		} else {
			return $this->{$class};
		}
	}

	public function get($_model)
	{ 
		$model = ucfirst($_model).'Model';
		if(isset($this->{$model})) {
			return $this->{$model};
		} else {
			return $this->load($_model);
		}
	}
	public function offsetExists($offset) {
		return isset($this->{$offset});
	}
	public function offsetGet($offset) {
		return $this->get($offset);
	}
	
	public function offsetSet($offset,  $value) {
		return true;
	}
	public function offsetUnset($offset) {
		return true;
	}
}
