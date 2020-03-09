<?php 

use Milkshake\Core\Router;

/** 
*
* Milkshake Router
* 
* Use the following format to create regular routes:
* Router::set(Method, Path, Controller.Method);
*
* For multiple methods per route, seperate methods with comma.
* 
* Use the following format to create redirect routes:
* Router::set('PATH', From, To);
* 
* Note: The method parameter also supports functions
*
**/

Router::set('GET', '/', 'MainController.index');

Router::set('GET', '/phpinfo', function() { 
    phpinfo(); 
});

?>