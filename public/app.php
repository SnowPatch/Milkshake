<?php 

use Milkshake\Loader;
use Milkshake\DotEnv;
use Milkshake\Controller;
use Milkshake\Router;

session_start();
require_once '../system/Loader.php';

try {

    Loader::init();
    DotEnv::init();
    Router::init();

} catch(Exception $e) {

    echo $e->getMessage();

}