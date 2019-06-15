<?php

namespace app\config;

class Config{

	private $loader;
	private $twig;
	private $assets = array('assets' => '/app/config/Resources/assets');

	function __construct(){
		$this->loader = new \Twig\Loader\FilesystemLoader(
			array (
        __ROOT__ . "web",
        __ROOT__ . "app/config/Resources",
        __ROOT__ . "app/config/Resources/layout",
			)
		);
		$this->twig = new \Twig_Environment($this->loader);

		$this->twig->addGlobal('Session', $_SESSION);
	}

	public function render($template, $vars = array()){
		$vars = array_merge($vars, $this->assets);
		echo $this->twig->render($template, $vars);
	}
}
