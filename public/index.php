<?php

use App\Entity\User;
use Jan\Component\Container\Container;


require_once __DIR__.'/../vendor/autoload.php';


$dotenv = \Jan\Component\Dotenv\Dotenv::create(__DIR__.'/../');
$dotenv->load();

echo getenv('APP_NAME') . "<br>\n";
echo $_ENV['DB_NAME'];

dump($dotenv->loadEnvironments());
