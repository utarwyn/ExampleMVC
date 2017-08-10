<?php
namespace Core\Error;

class Debugger {

	public function __construct() {

	}


	public static function getInstance($class = null) {

	}


	public static function trimPath($path)
	{
		if (defined('APP') && strpos($path, APP) === 0)
			return str_replace(APP, 'APP/', $path);
		if (defined('CAKE_CORE_INCLUDE_PATH') && strpos($path, CAKE_CORE_INCLUDE_PATH) === 0)
			return str_replace(CAKE_CORE_INCLUDE_PATH, 'CORE', $path);
		if (defined('ROOT') && strpos($path, ROOT) === 0)
			return str_replace(ROOT, 'ROOT', $path);

		return $path;
	}

}