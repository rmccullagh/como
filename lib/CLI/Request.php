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
use \CLI\ArgumentException;
class Request {
	protected $uri;
	protected $arguments;

	public function __construct($argv) {
		$this->arguments = $argv;
		$this->parse();	
	}
	protected function parse() {
		$arguments = array_shift($this->arguments);
		try {
			if(!is_array($this->arguments) || (is_array($this->arguments) AND count($this->arguments) === 0)) {
				throw new ArgumentException("php __init__.php [--class][--method]\n");
			}
			array_map(function($arg) {	
				$arg 					= str_replace('--', '', $arg);
				$arg 					= explode('-', $arg);
				$formated_arg	= NULL;
				if(is_array($arg)) {
					foreach($arg as $key => $value) {
						$word = ucfirst($value);
						$formated_arg .= $word;
					}
					$this->uri[] 	= $formated_arg;
				} else {
					$this->uri[]  = $arg;
				}
			}, $this->arguments);
		} catch(ArgumentException $e) {
			exit($e->getMessage());
		}
	}
	public function getArguments() {
		return $this->uri;
	}
}
