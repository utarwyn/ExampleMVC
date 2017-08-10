<?php
namespace App\Controller;

use Core\Controller\Controller;


class AssetsController extends Controller {

	public function index() {
		$assets = $this->getTable()->all();

		foreach ($assets as $entity)
			var_dump($entity->url);

		$this->render('assets.index', compact('assets'));
	}

}
?>