<?php 

namespace Milkshake;

class Validate {

  public function string($x, $min = 1, $max = 255) {
    return (is_string($x) && strlen($x) >= $min && strlen($x) <= $max);
  }

  public function int($x, $min = 0, $max = 999999) {
    return (is_int($x) && $x >= $min && $x <= $max);
  }

  public function float($x) {
    return is_float($x);
  }

  public function bool($x) {
    return is_bool($x);
  }

  public function array($x) {
    return is_array($x);
  }

  public function object($x) {
    return is_object($x);
  }

  public function function($x) {
    return is_callable($x);
  }

  public function email($x) {
    return filter_var($x, FILTER_VALIDATE_EMAIL);
  }

  public function ip($x) {
    return filter_var($x, FILTER_VALIDATE_IP);
  }

  public function url($x) {
    return filter_var($x, FILTER_VALIDATE_URL);
  }
	
}