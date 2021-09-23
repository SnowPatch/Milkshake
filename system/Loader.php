<?php 

namespace Milkshake; 

class Loader {

	private static $locations = [
    'system/DotEnv.php',
    'system/Csrf.php',
    'system/Request.php',
    'system/Router.php',
    'system', 
    'vendor/autoload.php',
		'model', 
    'controller', 
    'routes', 
	];

	public static function isPHP($file) {
    return strtolower(substr($file, -4)) === ".php";
	}
	
	private static function autoload($target) {

    if (is_file($target) && self::isPHP($target)) {
      require_once $target;
    }

    if (is_dir($target)) {

      $files = glob($target.'/*');
		
      foreach($files as $file) {
        self::autoload($file);
      }

    }
		
  }
	
	public static function load() {

		foreach (self::$locations as $target) {
			self::autoload(__DIR__.'/../'.$target);
		}
		
	}

}