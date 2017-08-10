<?php
namespace Core\Database\Exception;

use Core\Exception\Exception;


class MissingExtensionException extends Exception {

	protected $_message = "Le driver %s ne peut pas être utilisé car il manque l'extension PHP liée.";

}