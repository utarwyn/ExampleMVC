<?php
namespace Core\Database;

use Core\Database\Exception\MissingDriverException;
use Core\Database\Exception\MissingExtensionException;

class Connection {

	protected $_config;
	/** @var $_driver Driver */
	protected $_driver;
	protected $_logger;

	protected $_logQueries = false;


	public function __construct($config) {
		$this->_config = $config;

		$driver = '';
		if (!empty($config['driver']))
			$driver = $config['driver'];

		$this->setDriver($driver, $config);
	}

	public function __destruct() {
		unset($this->_driver);
	}


	public function setDriver($driver, $config = []) {
		$className = "Driver/$driver";
		if (!class_exists($className))
			throw new MissingDriverException(["driver" => $driver]);

		/** @var Driver $driver */
		$driver = new $className($config);

		if (!$driver->enabled())
			throw new MissingExtensionException(['driver' => get_class($driver)]);

		$this->_driver = $driver;
		return $this;
	}



	public function prepare($sql) {
		$statement = $this->_driver->prepare($sql);
		if ($this->_logQueries) $statement = $this->_newLogger($statement);

		return $statement;
	}

	/**
	 * @param $sql string La requête à executer
	 * @return mixed
	 */
	public function query($sql) {
		$statement = $this->prepare($sql);
		$statement->execute();
		return $statement;
	}

}