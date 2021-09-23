<?php 

namespace Milkshake; 

use Milkshake\View;
use Milkshake\Request;

class Controller {

	public $request;

	function __construct() {

		$this->request = new Request;
		$this->request->csrf()->detect();

	}
	
	public function model($name) {

		$model = '\Milkshake\Model\\'.$name;
		return new $model();

	}
	
	public function render($target, $data = false) {
		(new View())->render($target, $data);
	}

}