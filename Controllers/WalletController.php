<?php 

namespace Controllers;

use Config\Config;

/**
 * homepage
 */
class WalletController extends Config {

	public function gamer($pag='index') {
		$this->$pag();
	}

	public function index() {
		$this->render('wallet/index.twig');
	}

	public function deposito() {
		$this->render('wallet/deposito.twig');
	}
}
