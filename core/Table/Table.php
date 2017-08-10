<?php
namespace Core\Table;

class Table {

	protected $table;
	protected $db;


	public function __construct($db) {
		$this->db = $db;

		// Détection automatique du nom de la table à utiliser
		if (is_null($this->table)) {
			$parts      = explode('\\', get_class($this));
			$class_name = end($parts);

			$this->table = strtolower(str_replace('Table', '', $class_name)) . 's';
		}
	}


	public function find($id) {
		return $this->db->prepare("
			SELECT * FROM {$this->table}
			WHERE id = ?
		", [$id], $this->getEntityClass(), true);
	}

	public function query($statement, $attributes = null, $one = false) {
		if ($statement instanceof Query)
			$statement = strval($statement);

		if ($attributes)
			return $this->db->prepare($statement, $attributes, $this->getEntityClass(), $one);
		else
			return $this->db->query($statement, $this->getEntityClass(), $one);
	}

	public function all() {
		return $this->db->query("
			SELECT * from {$this->table}
		", $this->getEntityClass());
	}


	private function getEntityClass() {
		$class_name = get_class($this);
		$entity_class = str_replace('Table', 'Entity', $class_name);

		return $entity_class;
	}

}
?>