<?php 
session_start();
date_default_timezone_set('America/Bogota');

use app\Autoload;
use app\config\Request;
use app\config\Router;

define('__DS__', DIRECTORY_SEPARATOR);
define('__ROOT__', realpath(dirname(__FILE__)) . __DS__);

include_once(__ROOT__ . 'app/Autoload.php');
include_once(__ROOT__ . 'vendor/autoload.php');

Autoload::run();
$request = new Request();
new Router($request);