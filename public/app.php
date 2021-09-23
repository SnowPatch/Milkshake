<?php 

use Milkshake\Loader;
use Milkshake\DotEnv;
use Milkshake\Controller;
use Milkshake\Router;

session_start();
require_once '../system/Loader.php';

Loader::load();
DotEnv::load();

try {

  Router::run();

} catch(Exception $e) {
  echo $e->getMessage();
}