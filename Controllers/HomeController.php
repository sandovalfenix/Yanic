<?php 

namespace Controllers;

use Config\Config;

/**
 * @start "Homepage"
 */
class HomeController extends Config {

	public function home() {
		$this->render('home/index.twig');
	}
}
