<?php

namespace app\config;

class Request {
	
	private $render;
	private $twig;

	public function getRender(){
		return $this->render;
	}
	public function setRender($render){
		$this->render = $render;
	}

	public function getTwig(){
		return $this->twig;
	}
	public function setTwig($twig){
		$this->twig = $twig;
	}

	public function __construct() {
		
		if (!empty($_GET['render'])) {
			extract($_GET);
			$this->setRender($render);
		}
	}
}
