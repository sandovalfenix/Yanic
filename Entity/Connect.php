<?php 

namespace Entity;
use \PDO;
use \PDOException;

class Connect extends PDO { 
   private $type = 'mysql';
   private $name = '';
   private $host = 'localhost';
   private $user = 'root';
   private $pass = '';
   private $port = '';
   

   public function __construct() {
      try{
         parent::__construct($this->type.':host='.$this->host.';
            port='.$this->port.';
            dbname='.$this->name, $this->user, $this->pass, 
            array($this::ATTR_ERRMODE => $this::ERRMODE_EXCEPTION, $this::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')
         );
      }catch(PDOException $e){
         echo 'Ha surgido un error y no se puede conectar a la base de datos. Detalle: ' . $e->getMessage();
      }
   }
} 

