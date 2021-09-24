<?php 

namespace Milkshake;

use Milkshake\Csrf;

class Request 
{

	function csrf(): Csrf 
	{
		return new Csrf;
	}

	public function path(): string 
	{
		return trim($_GET['_uri']);
	}

  	public function url(): string 
  	{

    	$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
		return $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	}

  	public function method(): string 
	  {
		return strtoupper($_SERVER['REQUEST_METHOD']);
	}

  	public function get(string $key = '', string $default = '') 
	{

		if ($key) {

			if (is_array($key)) {

				$data = [];
	
				foreach ($key as $i) {
					$value = ($this->is('post')) ? $_POST[$i] : $_GET[$i];
					$data[$i] = $value ?? '';
				}
	
				return (object)$data;
	
			}

			return ($this->is('post')) ? ($_POST[$key] ?? '') : ($_GET[$key] ?? '');

		}

		return ($this->is('post')) ? (object)$_POST : (object)$_GET;

	}

  	public function is($method): bool 
	{
		return ($this->method() === strtoupper($method));
	}
	
}