<?php 
namespace App\Config;

use App\Config\Config;

class Router extends Config{
	
	public function __construct(Request $request) {
		parent::__construct();
		$path =__ROOT__."/web/";
		if (file_exists($path.$request->getRender().".twig")) {
			$this->render($request->getRender().".twig");
		}elseif(file_exists($path.$request->getRender()."/index.twig")) {
			$this->render($request->getRender()."/index.twig");
		}else{
			echo "el fichero ".$request->getRender()." no existe";
		}		
	}
}
