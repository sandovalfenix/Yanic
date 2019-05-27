<?php 
session_start();
date_default_timezone_set('America/Bogota');

use App\Autoload;
use App\Config\Request;
use App\Config\Router;

define('__DS__', DIRECTORY_SEPARATOR);
define('__ROOT__', realpath(dirname(__FILE__)) . __DS__);

include_once(__ROOT__ . 'app/autoload.php');
include_once(__ROOT__ . 'vendor/Twig/Autoloader.php');

Twig_Autoloader::register();
Autoload::run();
$request = new Request();
new Router($request);


