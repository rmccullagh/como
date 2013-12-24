<?php
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
				throw new ArgumentException("boominaudio[-a][-b][class][method]\n");
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
