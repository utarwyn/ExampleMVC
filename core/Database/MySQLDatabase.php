<?php
namespace Core\Database;

use PDO;

class MySQLDatabase extends Database {

	private $db_name;
	private $db_user;
	private $db_pass;
	private $db_host;

	private $pdo;


	private function __construct($db_name, $db_user, $db_pass, $db_host) {
		$this->db_name = $db_name;
		$this->db_user = $db_user;
		$this->db_pass = $db_pass;
		$this->db_host = $db_host;
	}

	public function getPDO() {
		if (is_null($this->pdo)) {
			$pdo = new PDO(
				'mysql:host=' . $this->db_host . ';dbname=' . $this->db_name . ';charset=UTF8', 
				$this->db_user, 
				$this->db_pass
			);

			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo = $pdo;
		}

		return $this->pdo;
	}


	public function query($statement, $class_name, $one = false) {
		$req   = $this->getPDO()->query($statement);
		$req->setFetchMode(PDO::FETCH_CLASS, $class_name);

		if ($one)  $datas = $req->fetch();
		else       $datas = $req->fetchAll();

		return $datas;
	}

	public function prepare($statement, $attributes, $class_name, $one = false) {
		$req = $this->getPDO()->prepare($statement);
		
		$req->execute($attributes);
		$req->setFetchMode(PDO::FETCH_CLASS, $class_name);

		if ($one)  $datas = $req->fetch();
		else       $datas = $req->fetchAll();

		return $datas;
	}



	public static function newInstance($dbConfig) {
		if ($dbConfig == null) return null;

		return new MySQLDatabase(
			$dbConfig["db_name"],
			$dbConfig["db_user"],
			$dbConfig["db_pass"],
			$dbConfig["db_host"]
		);
	}

}
?>