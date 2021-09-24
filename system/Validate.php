<?php 

namespace Milkshake;

class Validate 
{

    public function string($x, int $min = 1, int $max = 255): bool 
    {
        return (is_string($x) && strlen($x) >= $min && strlen($x) <= $max);
    }

    public function int($x, int $min = 0, int $max = 999999): bool 
    {
        return (is_int($x) && $x >= $min && $x <= $max);
    }

    public function float($x): bool 
    {
        return is_float($x);
    }

    public function bool($x): bool 
    {
        return is_bool($x);
    }

    public function array($x): bool 
    {
        return is_array($x);
    }

    public function object($x): bool 
    {
        return is_object($x);
    }

    public function function($x): bool 
    {
        return is_callable($x);
    }

    public function email(string $x): bool 
    {
        return filter_var($x, FILTER_VALIDATE_EMAIL);
    }

    public function ip(string $x): bool 
    {
        return filter_var($x, FILTER_VALIDATE_IP);
    }

    public function url(string $x): bool 
    {
        return filter_var($x, FILTER_VALIDATE_URL);
    }
	
}