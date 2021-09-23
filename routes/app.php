<?php 

use Milkshake\Router;

Router::get('/', 'AppController.index')->name('home');