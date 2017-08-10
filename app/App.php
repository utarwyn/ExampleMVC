<?php
define('DS'      , DIRECTORY_SEPARATOR);
define('APP'     , dirname(__FILE__));
define('ROOT'    , dirname(APP));
define('CORE'    , ROOT.DS.'core');
define('BASE_URL', dirname(dirname($_SERVER['SCRIPT_NAME'])));

use Core\Config;
use Core\Database\Connection;
use Core\Database\MySQLDatabase;
use Core\Routing\Dispatcher;

class App {

	private static $_dbInstance;

	private $dispatcher;


	public static function load() {
		require CORE . DS . 'Autoloader.php';
		require  APP . DS . 'Autoloader.php';
		Core\Autoloader::register();
		App\Autoloader::register();

		$dispatcher = new Dispatcher();
	}



	public static function getDb() {
		$conn = new Connection(array(
			"driver" => "PDODriverTrait"
		));
		return $conn;

		if (is_null(self::$_dbInstance))
			self::$_dbInstance = MySQLDatabase::newInstance(Config::getInstance()->getDbConfig());

		return self::$_dbInstance;
	}

}