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
namespace CLI;

class Dispatcher 
{	
	protected $request;
	protected $class;
	protected $method = 'init';
	protected $args;
	protected $controller_path;
	protected $messages = array();

	public function __construct(\CLI\Request $request)
	{
		$this->request = $request;
		$this->controller_path = BASE_PATH . '/controller/';
	}

	public function prepare()
	{	
		$mapType = $this->request->getArguments();

		if(self::isRoot($mapType)) 
		{
			$this->invokeRoot();
			return;
		} 
		for($i = 0; $i < count($mapType); $i++) {
			if($mapType[$i] == '') {
				unset($mapType[$i]);
				$mapType = array_values($mapType);
			}
		}
		if(count($mapType) > 0) {
			$parts = preg_split('/-/', $mapType[0]);
			$name = '';
			foreach($parts as $key => $value)
				$name .= ucfirst($value);
			$this->class = $name.'Controller';
			if(isset($mapType[1])) {
				$this->method = $mapType[1];				
			} else {
				$this->method = 'init';
			}			
			$args = array_slice($mapType, 2);		
			if(is_array($args)) {
					foreach($args as $key => $arg)
						$this->args[] = strtolower($arg);
			}
		} 
	}

	private function invokeRoot()
	{
		$this->class  = 'HomeController';
		$this->method = 'init';
	}

	public static function isRoot(array $node)
	{	
		return count($node) == 1 && $node[0] == '';
	}

	public function execute()
	{
		$class  = $this->class;
		$method = $this->method;
	
		if($class == '__construct' || $class == '__destruct')
			$this->show_404();
			
		if($method == '__construct' || $method == '__destruct')
			$this->show_404();
			
		if(file_exists(BASE_PATH . '/controller/'.$class.'.php'))
		{
			require_once BASE_PATH . '/controller/'.$class.'.php'; 
			$controller = new $class();
			$method = str_replace('-','_', $method);
			if(method_exists($controller, $method)) {
				$reflection = new \ReflectionMethod($controller, $method);
				if($reflection->isPublic()) {
					if($this->args) {
						$arg_count = $reflection->getNumberOfParameters();
						if(count($this->args) === $arg_count) {
							call_user_func_array(array($controller, $method), $this->args);
						} else {
							throw new ArgumentException('Valid method given, but incorrect number of raw arguments passed.');
						}
					} else {
						call_user_func(array($controller, $method));
					}
				} else {
					throw new \Exception('Cannot access private / protected methods');
				}
			} else {
				$this->show_404('Method not found');
			}
		} else {
			$this->show_404('File not found');
		}
	}
	private function show_404($message)
	{
		echo $message,  PHP_EOL;

		var_dump($this->class);
		var_dump($this->method);
		var_dump($this->args);
		exit;
	}
}
