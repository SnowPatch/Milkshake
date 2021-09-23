<?php 

namespace Milkshake;

use Milkshake\Router;
use Milkshake\Request;

class View {

	private $props;
	private $scope;

	public function __get($prop) {
		return $this->scope[$prop] ?? null;
	}

	public function __isset($name) {
		return isset($this->scope[$name]);
	}

	private function setProps($data) {

		if (is_array($data)) {

			foreach ($data as $key => $value) {
				$this->props[$key] = $value;
			}
			
		}

	}

	public function component($path, $data = false) {
		(new self())->render('components.'.$path, $data);
	}

	public function include($path) {
		$this->render($path);
	}

	public function render($path, $data = false) {

		$this->setProps($data);

		$path = str_replace('.', '/', $path);
		$file = '../view/'.$path.'.php';

		if (is_file($file)) {

			$request = new Request;

			$self = $request->path();

			$csrf = '<input type="hidden" name="csrf" value="'.$request->csrf()->create().'" />';

			$route = function($name, $params = []) { 
				return Router::find($name, $params); 
			};

			$post = function($key, $default = '') { 
				return $_POST[$key] ?? $default; 
			};

			include($file);

		}
		
	}

	public function props($props = []) {

		if (is_array($props)):
			foreach ($props as $key => $data):

				// Local prop
				if (isset($data['value'])) {
					$this->scope[$key] = $data['value'];
					continue;
				}

				// Apply default
				if (isset($data['default']) && !isset($this->props[$key])) {
					$this->scope[$key] = $data['default'];
					continue;
				}

				// Required
				if (isset($data['required']) && $data['required'] === true && !isset($this->props[$key])) {
					throw new \Exception('Property "'.$key.'" is required');
				}

				// Allowed values
				if (isset($data['allowed'], $this->props[$key]) && is_array($data['allowed']) && !in_array($this->props[$key], $data['allowed'])) {
					throw new \Exception('Property value for "'.$key.'" did not pass validation');
				}

				// Validate type
				if (isset($data['type'], $this->props[$key])) {

					$type = gettype($this->props[$key]);

					if (strtolower($type) !== strtolower($data['type'])) {
						throw new \Exception('Property "'.$key.'" expected type '.ucfirst($data['type']).', encountered '.ucfirst($type));
					}

				}

				// Save to scope
				$this->scope[$key] = $this->props[$key] ?? null;

			endforeach;
		endif;

	}
	
}