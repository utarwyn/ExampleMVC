<?php
namespace App\Entity;

use Core\Entity\Entity;

class AssetEntity extends Entity {

	public function getURL() {
		return '<a href="">' . $this->name .  '</a>';
	}

}
?>