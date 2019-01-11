<?php
namespace Entity;

use Entity\Connect; 


class addEntity extends Connect {
	public function __construct(){
		parent::__construct();
	}

	public function createTable($db){
		
		$Query = $this->prepare('SHOW FULL TABLES FROM '.$db);
		$Query->execute();
		$arrays = $Query->fetchAll($this::FETCH_ASSOC);
foreach ($arrays as $array => $tables) {

$tabla = ucwords($tables['Tables_in_'.$db]);
$Query = $this->prepare('SHOW COLUMNS FROM '.$tabla);
$Query->execute();
$arrays = $Query->fetchAll($this::FETCH_ASSOC);
$properties = array();

$atributos = '';
$attr = '';
$setter = '';
$values = '';
$bindParam = '';

$i=0;
foreach ($arrays as $array => $property) {
$atributos .= "private \$".$property['Field'].";\n\t";
if($property['Key'] == 'PRI'){ 
	$idProperty = $property['Field'];
}else{
	$attr .= ($i == 1) ? $property['Field'] : ", ".$property['Field'];
	$values .= ($i == 1) ? ":".$property['Field'] : ", :".$property['Field'];
	$bindParam .= "\$Query->bindParam(\":".$property['Field']."\", \$this->".$property['Field'].");\n\t\t";	
}
if ($property['Key'] == 'PRI' || $property['Key'] == 'MUL') {
	$setter .= "public function set".ucwords($property['Field'])."(\$".$property['Field'].") {
		\$Config = new Config();
		\$this->".$property['Field']." = \$Config->openCypher(\$".$property['Field'].", 'decrypt');
	}\n\t";	
}else{	
	$setter .= "public function set".ucwords($property['Field'])."(\$".$property['Field'].") {
		\$this->".$property['Field']." = \$".$property['Field'].";
	}\n\t";
}

$i++;
}

$new_file = fopen(__ROOT__.'Entity/'.$tabla.".php", "w+");
fwrite($new_file,"<?php

namespace Entity;

use Entity\Connect;
use Config\Config;

class ".$tabla." extends Connect {
	// properties
	".$atributos."	
	const TABLA = \"$tabla\";

	public function __construct(){
		parent::__construct();
	}

	// setters para obtencion de datos
	".$setter."
	//metodos para CRUD database
	public function save(){
		\$Query = \$this->prepare(\"INSERT INTO \".self::TABLA.\" (".$attr.") VALUES (".$values.")\");

		".$bindParam."
		return \$Query->execute();
	}

	public function read(\$col='*', \$property = NULL, \$value = NULL){
		\$complement = (!empty(\$property) && !empty(\$value)) ? \"WHERE \".\$property.\" = \".\$value : '';
		\$Query = \$this->prepare(\"SELECT \".\$col.\" FROM \".self::TABLA.\" \$complement ORDER BY ".$idProperty." DESC\");
		\$Query->execute();

		return \$Query->fetchAll(\$this::FETCH_ASSOC);
		
	}

	public function update(\$property = NULL, \$value = NULL){

	  	if(\$this->".$idProperty."){		    
		    \$Query = \$this->prepare(\"UPDATE \".self::TABLA.\" SET \".\$property.\" = \".\$value.\" WHERE ".$idProperty." = :".$idProperty."\");

		 	\$Query->bindParam(\":$idProperty\", \$this->".$idProperty.");
	  	}

	  	return \$Query->execute();
	}

	public function delete(){

		if(\$this->".$idProperty."){			
			\$Query = \$this->prepare(\"DELETE FROM \".self::TABLA.\" WHERE ".$idProperty." = :".$idProperty."\");
			\$Query->bindParam(\":$idProperty\", \$this->".$idProperty.");
	  	}
	  	return \$Query->execute();
	}

	public function row(\$col =\"*\"){

		if(\$this->".$idProperty."){			
			\$Query = \$this->prepare(\"SELECT \".\$col.\" FROM \".self::TABLA.\" WHERE ".$idProperty." = :".$idProperty."\");
			\$Query->bindParam(\":$idProperty\", \$this->".$idProperty.");
		}
		\$Query->execute();
		
		return \$Query->fetch(\$this::FETCH_ASSOC);
		
	}

	public function search(\$search, \$word, \$col=\"*\"){
		\$Query = \$this->prepare(\"SELECT \".\$col.\" FROM \".self::TABLA.\" WHERE \".\$search.\" LIKE %'\".\$word.\"'%\");
		\$Query->execute();
		
		return \$Query->fetchAll(\$this::FETCH_ASSOC);
	}

	public function __destruct(){

	}
}
	");

	fclose($new_file);
			echo "<h1>Tabla ".$tabla." fue creada con exito</h1>";		
		}
	}
}

