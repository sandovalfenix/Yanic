<?php 
session_start();
date_default_timezone_set('America/Bogota');

use Config\Autoload;
use Config\Request;
use Config\Router;

define('__DS__', DIRECTORY_SEPARATOR);
define('__ROOT__', realpath(dirname(__FILE__)) . __DS__);

include_once(__ROOT__ . 'Config/Autoload.php');
include_once(__ROOT__ . 'Vendors/Twig/Autoloader.php');

Twig_Autoloader::register();
Autoload::run();
$request = new Request();
new Router($request);


