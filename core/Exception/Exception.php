<?php
namespace core\Exception;

use RuntimeException;

class Exception extends RuntimeException {

	protected $_attributes = [];
	protected $_message;
	protected $_responseHeader;


	/**
	 * Permet de créer une exception
	 *
	 * @param string|array $message  Message de l'erreur ou un tableau des attributs à passer.
	 * @param int          $code     Code de l'erreur. Utilisée en tant que code retour HTTP.
	 * @param null         $previous La dernière exception.
	 */
	public function __construct($message, $code = 500, $previous = null) {
		if (is_array($message)) {
			$this->_attributes = $message;
			$message = vsprintf($this->_message, $message);
		}

		parent::__construct($message, $code, $previous);
	}


	/**
	 * @return array Retourne les attributs passés au constructeur
	 */
	public function getAttributes() { return $this->_attributes; }

}