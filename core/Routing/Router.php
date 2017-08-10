<?php
namespace Core\Routing;

class Router {

	public static function parse($url, $request) {
		$url    = trim($url, '/');
		$params = explode('/', $url);

		$request->controller = !empty($params[0]) ? $params[0] : 'index';
		$request->action     =  isset($params[1]) ? $params[1] : 'index';
		$request->params     = array_slice($params, 2);
	}

}
?>