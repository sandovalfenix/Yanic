<?php 
namespace Config;

use Config\Config;

class Router {
	
	public function __construct(Request $request) {
		
		$Config = new Config;
		
		$controller = (empty($request->getController())) ? $this->start_home(__ROOT__ . 'Controllers') : $request->getController();		
		if(file_exists(__ROOT__ . 'Controllers' . __DS__ . $controller .".php")){

			$class_controller = "Controllers\\" . $controller;
			$rc = new \ReflectionClass($class_controller);			
			$role = (strrpos($rc->getDocComment(), "@role")) ? explode(",", trim(str_replace(array('"', "*", "/", " ","@role"),"",$rc->getDocComment()))) : array(NULL);
		    $check = (isset($_SESSION['ROLE']) && !in_array(NULL, $role)) ? in_array(strtoupper($_SESSION['ROLE']), $role) : in_array(NULL, $role);

		    if ($check) {
		    	$object = new $class_controller;
		    	$method = (empty($request->getMethod())) ? 'home' : $request->getMethod();

				if (method_exists($object, $method)) {					
					$rm = new \ReflectionMethod($class_controller, $method);

					$role = (strrpos($rc->getDocComment(), "@role")) ? explode(",", trim(str_replace(array('"', "*", "/", " ", "@role"),"",$rc->getDocComment()))) : array(NULL);
					
				    $authenticate = (isset($_SESSION['ROLE']) && !in_array(NULL, $role)) ? in_array(strtoupper($_SESSION['ROLE']), $role) : in_array(NULL, $role);
				    if ($authenticate) {
					    $data = (empty($request->getArgument())) ? call_user_func(array($object, $method)) : call_user_func_array(array($object, $method), explode('$', $request->getArgument()));
					}
				}
				exit();		
			}
		}
		$Config->render("errors/error-404.twig", array(
			'session' => @$_SESSION['ROLE'],
		));
		
		
	}

	public function start_home($carpeta){
		$start_home = false;

	    if(is_dir($carpeta)){
	        if($dir = opendir($carpeta)){
	            while(($archivo = readdir($dir)) !== false && $start_home == false){
	                if($archivo != '.' && $archivo != '..' && $archivo != '.htaccess'){
	                	$rc = new \ReflectionClass("Controllers\\". str_replace(".php", "", $archivo));
						$start_home = (strrpos($rc->getDocComment(), "@start")) ? str_replace("Controllers\\", "", $rc->getName()) : false;
	                }
	            }
	            closedir($dir);
	        }
	    }
	    return $start_home;
	}
}
