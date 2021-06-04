<?php

use App\Entity\User;
use Jan\Component\DependencyInjection\Container;


require_once __DIR__.'/../vendor/autoload.php';


$container = new Container();

$container->bind('name', 'Жан-Клод');
$container->bind(User::class);


echo $container->get('name');
echo $container->get(User::class);
dd($container->getBindings());


