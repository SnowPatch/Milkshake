<?php 

namespace Milkshake; 

class Loader 
{
	
	private static function autoload(string $target): void 
    {

        if (strtolower(substr($target, -4)) === '.php' && is_file($target)) {

            require_once $target;

        } elseif (is_dir($target)) {

            $files = glob($target.'/*.php');
            
            foreach($files as $file) {
                self::autoload($file);
            }

        }

    }
	
	public static function init(): void
    {

        $locations = [
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

		foreach ($locations as $target) {
			self::autoload(__DIR__.'/../'.$target);
		}

	}

}