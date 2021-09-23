<?php 

namespace Milkshake;

class DotEnv {

  public static $loaded = [];

  public static function load($path = '.env') {

    $path = __DIR__.'/../'.$path;

    if (in_array($path, self::$loaded)) { return; }
    self::$loaded[] = $path;

    if (!file_exists($path)) { 
      throw new \Exception(sprintf('%s file does not exist', $path));
    }

    if (!is_readable($path)) { 
      throw new \Exception(sprintf('%s file is not readable', $path));
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

      if (strpos(trim($line), '#') === 0) { continue; }

      list($name, $value) = explode('=', $line, 2);
      $name = trim($name);
      $value = trim($value);

      putenv(sprintf('%s=%s', $name, $value));
      $_ENV[$name] = $value;
      $_SERVER[$name] = $value;

    }

  }
  
}