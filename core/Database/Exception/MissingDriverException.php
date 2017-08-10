<?php
namespace Core\Database\Exception;

use Core\Exception\Exception;


class MissingDriverException extends Exception {

	protected $_message = "Le Driver %s n'a pas été trouvé.";

}