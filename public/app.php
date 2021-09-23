<?php 

use Milkshake\Loader;
use Milkshake\DotEnv;
use Milkshake\Controller;
use Milkshake\Router;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../system/Loader.php';

Loader::load();
DotEnv::load();

try {

  Router::run();

} catch(Exception $e) {
  echo $e->getMessage();
}