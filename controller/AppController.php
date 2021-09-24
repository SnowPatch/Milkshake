<?php 

namespace Milkshake\Controller;

use Milkshake\Controller;

class AppController extends Controller 
{

	public function index() 
	{

		$data = [
			'text' => 'Milkshake Framework',
		];

		return $this->render('index', $data);

  }
	
}