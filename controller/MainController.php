<?php 

namespace Milkshake\Controller;

use Milkshake\Core\Controller;
use Milkshake\Model\MainModel;

class MainController extends Controller {

	public function Index() {
		
		$model = new MainModel;
		
		$data = array(
		  'data' => $model->Index()
		);
		
		return $this->render('index', $data);
		
	}
	
}

?>