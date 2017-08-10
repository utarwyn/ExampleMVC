<?php
namespace Core\Routing;

class Request {

	public $url;

	public $controller;
	public $action;
	public $params;


	public function __construct() {
		$this->url = (isset($_GET['uri'])) ? $_GET['uri'] : "";
	}

}
?>