<?php
namespace Core;

class Config {

	private static $_instance;

	private $data;


	private function __construct() {
		$configFile = ROOT . DS . 'config' . DS . 'config.php';

		if (file_exists($configFile)) {
			require $configFile;
			$this->data = (isset($config)) ? $config : array();
		}
	}


	public function getValue($key) {
		$value = null;
		$path  = explode('.', $key);

		foreach ($path as $v)
			if ($value != null && isset($value[$v]))
				$value = $value[$v];
			else if ($value == null && isset($this->data[$v]))
				$value = $this->data[$v];
			else
				$value = null;

		return $value;
	}

	public function getDbConfig() {
		$database = $this->getValue("database");

		if (is_array($database)) return $database;
		else                     return $this->getValue("databases." . $database);
	}



	public static function getInstance() {
		if (is_null(self::$_instance))
			self::$_instance = new Config();

		return self::$_instance;
	}

}
?>