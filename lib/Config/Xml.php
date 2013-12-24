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
namespace Config;
use DOMDocument;

class Xml
{
	private static $items = array();
	private $file; 
	private static $instance;
  public static function getInstance()
	{
		if(!self::$instance)
			self::$instance = new static();
		return self::$instance;
	}
	private  function __construct()
	{
		self::$items['database'] = array();
		
		$this->file=BASE_PATH.'/config/'.ENVIROMENT.'.xml';
		$this->parse();
	}
	private function parse()
	{
		$doc = new DOMDocument();
		$doc->load($this->file);
        $cn = $doc->getElementsByTagName("config");
        $database = $cn->item(0)->getElementsByTagName('database');
        $emails = $cn->item(0)->getElementsByTagName('email');
        foreach($emails as $email)
			self::$items[$email->nodeName] = $email->nodeValue;
        foreach($database as $d) 
        {
			$a = $database->item(0)->getElementsByTagName("*");
			foreach($a as $b)
				self::$items['database'][$b->nodeName] = $b->nodeValue;
		}
		
			
		//$nodes = $cn->item(0)->getElementsByTagName("*");
		//foreach($nodes as $node)
			//self::$items[$node->nodeName] = $node->nodeValue;
	}

	public function __get($id)
	{
		return self::$items[$id];
	}
	public function __isset($name)
	{
		return isset(self::$items[$name]);
	}
}
