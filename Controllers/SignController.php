<?php 

namespace Controllers;

use Config\Config;

/**
 * @start homepage
 */
class SignController extends Config {

	public function __construct() {
		parent::__construct();
		$this->index();
	}

	public function index() {
		$this->render('sign/sign-in.twig');
	}
}
