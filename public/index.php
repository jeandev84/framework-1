<?php

use App\Entity\User;
use Jan\Component\Container\Container;


require_once __DIR__.'/../vendor/autoload.php';



$dotenv = \Jan\Component\Dotenv\Dotenv::create(__DIR__.'/../');
$dotenv->load();

dump($dotenv->loadEnvironments('.env'));

echo getenv('APP_NAME');
