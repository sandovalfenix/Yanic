<?php 

namespace app;

class Autoload {
	public static function run() {	
		spl_autoload_register(function($class){	
			
			$path_file = str_replace("\\", "/", $class).".php";

			if (file_exists($path_file)) {
				include_once($path_file);
			}else{
				echo "<h2>Error en el auto cargado: ".__ROOT__.$path_file."</h2>
					  <p>El archivo no se encuetra o no existe</p>";
			}
		});
	}
}