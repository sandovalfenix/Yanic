<?php

/**
 *	Para crear una tabla : www.ejemplo.dev/Entity/AddEntity.php
 **/

$tabla = ucwords('Archivo'); 
$properties = array('id', 'nombre_corto', 'nombre_largo', 'extension', 'email', 'origen'); 

$atributos = '';
$attr = '';
$setter = '';
$values = '';
$bindParam = '';

$i=0;
foreach ($properties as $property) {	
	$atributos .= "private \$".$property.";\n\t";
	if($i==0){ 
		$idProperty = $property;
	}else{
		$attr .= ($i == 1) ? "$property" : ", $property";
		$values .= ($i == 1) ? ":$property" : ", :$property";
		$bindParam .= "\$Query->bindParam(\":$property\", \$this->".$property.");\n\t\t";	
	}	
	$setter .= "public function set".ucwords($property)."(\$".$property.") {
		\$this->".$property." = \$".$property.";
	}\n\t";
	
	$i++;
}


$new_file = fopen($tabla.".php", "w+");
fwrite($new_file,"<?php

namespace Entity;

use Entity\Connect;

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

echo "<h1>Archivo fue creado con exito</h1>";