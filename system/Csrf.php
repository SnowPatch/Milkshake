<?php 

namespace Milkshake;

class Csrf {

  public function create() {

    $secret = getenv('CSRF_SECRET');
    $seed = bin2hex(random_bytes(16));
    $hash = hash_hmac('sha256', session_id().$seed, $secret);
    return urlencode($hash.'@'.$seed);

  }

  public function validate($token) {

    $secret = getenv('CSRF_SECRET');
    $parts = explode('@', urldecode($token));
    $hash = hash_hmac('sha256', session_id().$parts[1], $secret);
    return hash_equals($hash, $parts[0]);

  }

  public function detect() {

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['csrf'])) {
      if (!$this->validate($_POST['csrf'])) {
        throw new \Exception('CSRF token failed validation');
      }
    }

  }
	
}