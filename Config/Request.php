<?php

namespace Config;

class Request {
	
	private $controller;
	private $method;
	private $argument;

	public function getController(){
		return $this->controller;
	}
	public function getMethod(){
		return $this->method;
	}
	public function getArgument(){
		return $this->argument;
	}
	public function setController($controller){
		$this->controller = ucfirst($controller)."Controller";
	}
	public function setMethod($method){
		$this->method = $method;
	}
	public function setArgument($argument){
		$this->argument = $argument;
	}

	public function __construct() {
		
		if (!empty($_GET['url'])) {
			$route = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
			$route = explode('/', $route);
			$route = array_filter($route);
			$this->setController(strtolower(array_shift($route)));
			$this->setMethod(strtolower(array_shift($route)));
			$this->setArgument(array_shift($route));			
		}
	}
}
