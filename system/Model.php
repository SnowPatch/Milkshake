<?php 

namespace Milkshake; 
use Milkshake\Request;

class Model {
	
	protected $pdo;
	public $request;
	public $validate;

	function __construct() {

		$this->request = new Request;
		$this->validate = new Validate;

	}
	
	protected function connect() {
		
		$type = getenv('DB_TYPE');
		$host = getenv('DB_HOST');
		$port = getenv('DB_PORT') ?: 3306;
		$name = getenv('DB_NAME');
		$user = getenv('DB_USER');
		$pass = getenv('DB_PASS');

		if (!$type || !$host || !$port || !$name || !$user) {
			throw new \Exception('Missing database connection parameters');
		}

		$options = [
			\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
			\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
			\PDO::ATTR_EMULATE_PREPARES => false,
		];

		$dsn = $type.':host='.$host.';dbname='.$name.';charset=utf8mb4;port='.$port;

		try { 

			$this->pdo = new \PDO($dsn, $user, $pass, $options); 

		} catch (\PDOException $e) { 
			throw new \Exception($e->getMessage()); 
		}

		return $this->pdo;
		
	}

	protected function disconnect() {
		$this->pdo = null;
	}

	public function return($status, $message = '') {
		return (object)[
			'status' => $status,
			'message' => $message,
		];
	}

	public function require($fields) {
		
		$status = true;
		$data = [];

		foreach ($fields as $name => $validator) {

			$value = $this->request->get($name);

			if ($this->validate->{$validator}($value)) {

				$data[$name] = $value;
				continue;

			} 

			$status = false;

		}

		return ($status) ? (object)$data : false;

	}

}