<?php 

namespace Milkshake;

use Milkshake\View;

class Router {

	private static 
		$routes = [], 
		$params;

	public function name($name) {
		self::$routes[count(self::$routes)-1]['name'] = $name;
	}

	public static function find($name, $params = []) {

		$key = array_search($name, array_column(self::$routes, 'name'));
		if ($key === false) { return ''; }

		$route = self::$routes[$key]['request'] ?? '';

		if ($route) {
			foreach ($params as $key => $param) {
				$route = str_replace('{'.$key.'}', $param, $route);
			}
		}

		return $route;
		
	}

	public static function isURL($url) {
		return (strpos($url, 'http') === 0 || strpos($url, 'www') === 0 || strpos($url, '/') === 0);
	}

	public static function add($method, $request, $target) {
		
		if (substr($request, 0, 1) !== '/') {
			throw new \Exception('Invalid route defined (Route.Route Error)');
		}
		
		if (!is_callable($target) && preg_match('/[^A-Za-z.]/', $target) !== 0 && !isURL($target)) {
			throw new \Exception('Invalid route target defined (Route.Target Error)');
		}
		
		self::$routes[] = [
			'name' => null,
			'method' => $method,
			'request' => $request,
			'target' => $target,
		];

		return new static;
		
	}

	public static function redirect($request, $target) {
		return self::add('GET', $request, $target);
	}

	public static function get($request, $target) {
		return self::add('GET', $request, $target);
	}

	public static function post($request, $target) {
		return self::add('POST', $request, $target);
	}

	private static function process($target) {

		if (self::isURL($target)) {

			/* Transfer variables between local routes */
			if (self::$params && strpos($target, '/') === 0) {
				foreach (self::$params as $variable => $value) {
					$target = str_replace('{'.$variable.'}', $value, $target);
				}
			}

			var_dump($target);
			//header('Location: '.$target); 
			exit;

		}

		if (is_callable($target)) {
			return $target(self::$params);
		}

		$parts = explode('.', $target);
		$class = 'Milkshake\Controller\\'.$parts[0];
		$method = $parts[1];
	
		return (new $class())->$method(self::$params);

	}

	public static function run() {

		$uri = trim($_GET['_uri']);
		$method = $_SERVER['REQUEST_METHOD'];
		$slashes = substr_count($uri, '/'); 

		$chunks = explode('/', $uri);

		// Get all route keys where method matches
		$keys = array_keys(array_column(self::$routes, 'method'), $method);

		foreach ((array)$keys as $key):

			$route = self::$routes[$key];
			$target = $route['target'];

			if (!$target) {
				continue;
			}

			if ($slashes !== substr_count($route['request'], '/')) {
				continue;
			}

			if ($route['request'] == $uri) { 
				self::process($target); 
				return;
			}

			if (strpos($route['request'], '{') !== false) {

				self::$params = [];
				$request = '';
				$target = '';

				foreach (explode('/', $route['request']) as $key => $value) {

					if (substr($value, 0, 1) === '{' && substr($value, -1) === '}') {

						self::$params[preg_replace('/\{+||\}+/', '', $value)] = $chunks[$key];
						$request .= '/';
						$target .= '/';

					} else {

						$request .= '/'.$chunks[$key];
						$target .= '/'.$value;

					}

				}

				if ($target == $request) {
					self::process($route['target']);
					return;
				}

			}

		endforeach;

		throw new \Exception('Unknown Route');

	}
	
}