<?php 

namespace Milkshake\Core;

class Router {

	private static $routes = [];
	
	public static function set($method, $request, $action) {
		
		/* Validate request route */
		if (strpos($request, '/') === false) {
			die('Something went wrong (Route.Route Error)');
		}
		
		if ($method === "PATH") {
			
			/* Validate redirect target */
			if (strpos($action, '/') === false) {
				die('Something went wrong (Route.Redirect.Target Error)');
			}
			
		} else {
			
			/* Validate request target */
			if (!is_callable($action) && strpos($action, '.') === false) {
				die('Something went wrong (Route.Target Error)');
			}
			
		}
		
		/* Save route */
		Router::$routes[$request] = [
			'method' => $method,
			'action' => $action, 
		];
		
	}

	private static function match($request, $routes) {

		$matches = 0;
		$match_arr = [];
		
		foreach ($routes as $key => $value) {
			
			/* Exact match for route */
			if($request == $key) {
				
				if($value['method'] === "PATH") {
					
					header('Location: '.$value['action']); die();
					
				} else {
					
					/* Validate method */
					if (!in_array($_SERVER['REQUEST_METHOD'], explode(',', $value['method']))) {
						die('Something went wrong (Request.Method Error)');
					}
					
					return $value;
					
				}
				
			}
			
			/* Parameter matching */
			if(preg_match('({|})', $key) && substr_count($key, '/') === substr_count($request, '/')) {
				
				$matches++;
				
				$paramless = preg_replace('/{(.*?)}/', '', $key);
				
				$sim = similar_text($request, $paramless, $percentage);
				$match_arr[$key] = $percentage;
				
			}
		 
		}
		
		/* An array of likely matches exists */
		if($matches > 0) {
			
			/* Get match with highest percentage */
			$highest = array_search(max($match_arr), $match_arr);
			
			/* Target corresponding original route */
			$target = $routes[$highest];
			
			/* Validate method */
			if (!in_array($_SERVER['REQUEST_METHOD'], explode(',', $target['method']))) {
				die('Something went wrong (Request.Method Error)');
			}
			
			$param__arr = [];
			$parts_request = array_filter(explode("/", $request));
			$parts_route = array_filter(explode("/", $highest));
			
			foreach ($parts_route as $key => $part) {
				
				if(preg_match('({|})', $part)) {
					
					preg_match('/{(.*?)}/', $part, $match);
					$id = $match[1];
					$value = $parts_request[$key];
					
					/* Save value in array with parameter name as key */
					$param_arr[$id] = $value;
					
				}
				
			}
			
			$target['data'] = $param_arr;
			
			return $target;
			
		}
		
		/* No match found for supplied route */
		die('Something went wrong (Unknown Route)');

	}
	
	public static function load($request) {

		return Router::match(trim($request), Router::$routes);
		/*return Router::$routes;*/

	}
	
}

?>