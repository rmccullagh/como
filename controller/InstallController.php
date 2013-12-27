<?php
class InstallController extends BaseController {
	public function __construct() {
		parent::__construct();
	}
	public function init($name) {
		$name = ucfirst($name);
		$controller = $name.'Controller';

		$text  = '<?php';
		$text .= "\n";	
		$text .= "class $controller extends BaseController {\n";
		$text .= "\tpublic function __construct() {\n";
		$text .= "\t\tparent::__construct();\n";
		$text .= "\t}\n";
		$text .= "\tpublic function init() {\n";
		$text .= "\t\techo __METHOD__ . PHP_EOL;\n";
		$text .= "\t}\n";
		$text .= "}";

		file_put_contents(BASE_PATH.'/controller/'.$controller.'.php', $text);
	}
}
