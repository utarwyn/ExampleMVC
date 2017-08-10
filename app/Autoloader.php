<?php
namespace App;

/**
 * Autoloader
 */
class Autoloader {

	/**
	 * Enregistre notre autoloader
	 */
	public static function register() {
		spl_autoload_register(array(__CLASS__, 'autoload'));
	}

	/**
     * Inclue le fichier correspondant à notre classe
     * @param $class string Le nom de la classe à charger
     */
	private static function autoload($class) {
		if (strpos($class, __NAMESPACE__ . '\\') === 0) {
			$class = str_replace(__NAMESPACE__ . '\\', '', $class);
			$class = str_replace('\\', DS, $class);
			require dirname(__FILE__) . DS . $class . '.php';
		}
	}

}
?>