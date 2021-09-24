<?php 

namespace Milkshake;

class Csrf 
{

    public function create(): string 
    {

        $secret = getenv('CSRF_SECRET');
        $seed = bin2hex(random_bytes(16));
        $hash = hash_hmac('sha256', session_id().$seed, $secret);

        return urlencode($hash.'@'.$seed);

    }

    public function validate(string $token): bool 
    {

        $secret = getenv('CSRF_SECRET');
        $parts = explode('@', urldecode($token));
        $hash = hash_hmac('sha256', session_id().$parts[1], $secret);
        
        return hash_equals($hash, $parts[0]);

    }

    public function detect(): void 
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['_csrf'])) {
            if (!$this->validate($_POST['csrf'])) {
                throw new \Exception('CSRF token failed validation');
            }
        }

    }
	
}