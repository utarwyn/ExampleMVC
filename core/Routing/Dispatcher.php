<?php
namespace Core\Routing;

class Dispatcher {

	private $request;


	public function __construct() {
		$this->request = new Request();
		Router::parse($this->request->url, $this->request);

		// On appele le Controller qui gère cette page
		$controller = $this->loadController();
		$action     = $this->request->action;

		$controller->$action($this->request->params);
	}


	private function loadController() {
		$name = '\App\Controller\\' . ucfirst($this->request->controller) . 'Controller';

		// Si le controller n'existe pas, on lance une erreur
		if (!file_exists(ROOT . $name . '.php'))
			die('Le controller ' . ucfirst($this->request->controller) . ' n\'existe pas !');

		return new $name();
	}

}
?>