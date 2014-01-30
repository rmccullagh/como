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
use \Cavalry\Log;

class Dispatcher 
{	
	protected $request;
	protected $class;
	protected $method = 'init';
	protected $args;
	protected $controller_path;
        protected $messages = array();
        protected $log;

	public function __construct(\CLI\Request $request)
        {
            $this->log = Log::getInstance();
            $this->log->push(__METHOD__, "Initializing Dispatcher");
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
			//echo count($mapType),  PHP_EOL;		
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
/*	
		if($class == '__construct' || $class == '__destruct')
			$this->show_404('dfdf');
			
		if($method == '__construct' || $method == '__destruct')
			$this->show_404('dfdf');
 */			
		if(file_exists(BASE_PATH . '/controller/'.$class.'.php'))
                {
                    $this->log->push(__METHOD__, 
                    "found controller for requested class"
                    );
                    require_once BASE_PATH . '/controller/'.$class.'.php';
                    $c = new \ReflectionClass($class);
                    var_dump($c);
			$controller = new $class();
                        $method = str_replace('-','_', $method);
                        if(method_exists($controller, $method)) {
                            $this->log->push(__METHOD__, 
                            "method found for requested controller"
                            );
                            $reflection = new \ReflectionMethod($controller, $method);
                            $this->log->push(__METHOD__, 
                                "isConstructor: " . $reflection->isConstructor()
                            );
                            if($reflection->isConstructor() || $reflection->isDestructor()) {
                                $this->log->push(__METHOD__, 
                                    "method was a destructor or constructor, aborting", 
                                    LOG::LEVEL_NOTICE
                                );
                                exit;
                            }

                            if($reflection->isPrivate() || $reflection->isProtected()) {
                                $this->log->push(__METHOD__, 
                                    "method was a private or protected, aborting", 
                                    LOG::LEVEL_NOTICE
                                );
                                exit;
                            }
                                if($reflection->isPublic()) {
                                    $this->log->push(__METHOD__, 
                                    "method is public"
                                    );
                                    if($this->args) {
                                        $this->log->push(__METHOD__, 
                                        "request had arguments"
                                        );
					$arg_count = $reflection->getNumberOfParameters();
                                        if(count($this->args) === $arg_count) {
                                            $this->log->push(
                                                __METHOD__, 
                                                "requested argument count matched requested method argument count" 
                                            );
                                            $reflection->invokeArgs($controller, $this->args);
                                            //call_user_func_array(array($controller, $method), $this->args);
                                        } else {
                                            $this->log->push(__METHOD__, 
                                                "valid method given, but incorrect number of raw arguments passed", 
                                                LOG::LEVEL_NOTICE
                                            );
					    throw new ArgumentException('Valid method given, but incorrect number of raw arguments passed.');
					}
                                    } else {
                                        // test that the method does not have arguemnts
                                        $arg_count = $reflection->getNumberOfParameters();
                                        $this->log->push(__METHOD__, 
                                            "request did not have arguments"
                                        );
                                        if(count($this->args) === $arg_count) {
                                            call_user_func(array($controller, $method));
                                        } else {
                                            $this->log->push(__METHOD__, 
                                                "request tried to invoke a method without required number of arguments", 
                                                LOG::LEVEL_NOTICE
                                            );
                                        }
				    }
                                } else {
                                    $this->log->push(__METHOD__, 
                                        "tried to access a private or protected method, aborting", 
                                        LOG::LEVEL_NOTICE
                                    );
					throw new \Exception('Cannot access private / protected methods');
				}
                        } else {
                            $this->log->push(__METHOD__, 
                                "method was not found for request", 
                                LOG::LEVEL_NOTICE
                            );
				$this->show_404('Method not found');
			}
                } else {
                    $this->log->push(__METHOD__, 
                        "file was not found for requested controller", 
                        LOG::LEVEL_NOTICE
                    );
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
