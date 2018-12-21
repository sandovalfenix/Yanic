<?php 

namespace Controllers;

use Config\Config;

/**
 * homepage
 */
class GamesController extends Config {

	public function gamer($page = 'index') {
		$this->$page();
	}

	public function index() {
		$this->render('games/index.twig');
	}

	public function select(){
		$this->render('games/select.twig');
	}
}
