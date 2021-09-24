<?php 

namespace Milkshake;

use Milkshake\View;

class Router 
{

	private static $routes;
	private static $params;

	public function name(string $name): void
	{

		$latest = count(self::$routes) - 1;
		self::$routes[$latest]['name'] = $name;

	}

	public static function find(string $name, array $params = []): string
	{

		$key = array_search($name, array_column(self::$routes, 'name'));

		if ($key === false) { 
			return ''; 
		}

		$route = self::$routes[$key]['request'] ?? '';

		foreach ($params as $key => $param) {
			$route = str_replace('{'.$key.'}', $param, $route);
		}

		return $route;
		
	}

	public static function isRouteOrURL($url): bool
	{
		return (is_string($url) && strpos($url, '/') === 0);
	}

	public static function add(string $method, string $request, $target): Router
	{

		if (substr($request, 0, 1) !== '/') {
			throw new \Exception('Invalid route defined (Route.Route Error)');
		}
		
		if (!is_callable($target) && preg_match('/[^A-Za-z.]/', $target) !== 0 && !self::isRouteOrURL($target)) {
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

	public static function redirect(string $request, $target): Router 
	{
		return self::add('GET', $request, $target);
	}

	public static function get(string $request, $target): Router 
	{
		return self::add('GET', $request, $target);
	}

	public static function post(string $request, $target): Router 
	{
		return self::add('POST', $request, $target);
	}

	private static function process($target)
	{

		if (self::isRouteOrURL($target)) {

			/* Transfer variables between local routes */
			if (self::$params && strpos($target, '/') === 0) {
				foreach (self::$params as $variable => $value) {
					$target = str_replace('{'.$variable.'}', $value, $target);
				}
			}

			header('Location: '.$target); 
			exit;

		}

		if (is_callable($target)) {
			return $target(self::$params);
		}

		$parts = explode('.', $target);
		$class = 'Milkshake\Controller\\'.$parts[0];
		$method = $parts[1];

		$result = (new $class())->$method(self::$params);
		return $result;

	}

	public static function init(): void
	{

		$uri = trim($_GET['_uri']);
		$method = $_SERVER['REQUEST_METHOD'];
		$slashes = substr_count($uri, '/'); 

		$chunks = explode('/', $uri);

		// Get all route keys where method matches request method
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