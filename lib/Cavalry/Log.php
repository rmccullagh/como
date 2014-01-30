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
namespace Cavalry;
class Log 
{
    const LEVEL_INFO	= "INFO";	
    const LEVEL_NOTICE	= "NOTICE";

    private $_log_path;	
    private static $_instance;
	
    private function __construct($path) {
        
        $this->_log_path=$path;
    
    } 
    private function __clone() {} 
	
    public static function getInstance($path=NULL) { 
        
        if(!self::$_instance) {
            self::$_instance = new static($path);
        }
        return self::$_instance; 

    }
    public function push($function_name, $message, $level = "INFO") {	
        
        $timestamp = date('m/d/Y h:i:s A');
        $line = $timestamp." - ".$function_name." - ";
        switch($level) {
            case 'INFO':
                $line .= "\033[0;32m".$level;
                $line .= "\033[0m";
            break;
            case 'NOTICE':
                $line .= "\033[0;31m".$level;
                $line .= "\033[0m";
            break;
        }
        $line .= " - ".$message;
        echo $line, "\n"; 
    
    }
}







