<?php
namespace Core\Database\Driver;

use MongoDB\Driver\Query;
use PDO;
use PDOStatement;

trait PDODriverTrait {

	protected $_connection;


	protected function _connect($dsn, array $config) {
		$connection = new PDO(
			$dsn,
			$config['username'],
			$config['password'],
			$config['flags']
		);

		$this->connection($connection);
		return true;
	}

	public function connection($connection = null) {
		if ($connection !== null)
			$this->_connection = $connection;

		return $this->_connection;
	}


	public function disconnect() {
		$this->_connection = null;
	}

	public function isConnected() {
		if ($this->_connection === null)
			$connected = false;
		else {
			try {
				$connected = $this->_connection->query('SELECT 1');
			} catch (\PDOException $e) {
				$connected = false;
			}
		}
		return (bool)$connected;
	}

	public function prepare($query) {
		$this->connect();
		$isObject = $query instanceof Query;
		$statement = $this->_connection->prepare($isObject ? $query->sql() : $query);
		return new PDOStatement($statement, $this);
	}

	public function beginTransaction() {
		$this->connect();
		if ($this->_connection->inTransaction()) return true;

		return $this->_connection->beginTransaction();
	}

	public function commitTransaction() {
		$this->connect();
		if (!$this->_connection->inTransaction()) return false;

		return $this->_connection->commit();
	}

	public function rollbackTransaction() {
		$this->connect();

		if (!$this->_connection->inTransaction())
			return false;

		return $this->_connection->rollback();
	}

	public function quote($value, $type) {
		$this->connect();
		return $this->_connection->quote($value, $type);
	}

	public function lastInsertId($table = null, $column = null) {
		$this->connect();
		return $this->_connection->lastInsertId($table);
	}


	public function supportsQuoting() {
		$this->connect();
		return $this->_connection->getAttribute(PDO::ATTR_DRIVER_NAME) !== 'odbc';
	}

}