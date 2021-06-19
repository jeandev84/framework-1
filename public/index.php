<?php


/*
|----------------------------------------------------------------------
|   Autoloader classes and dependencies of application
|----------------------------------------------------------------------
*/

require_once __DIR__.'/../vendor/autoload.php';



/*
|-------------------------------------------------------
|    Require bootstrap of Application
|-------------------------------------------------------
*/

$app = require_once __DIR__.'/../bootstrap/app.php';



/*
|-------------------------------------------------------
|    Check instance of Kernel
|-------------------------------------------------------
*/

$kernel = $app->get(Jan\Contract\Http\Kernel::class);


/*
|-------------------------------------------------------
|    Get Response
|-------------------------------------------------------
*/


$response = $kernel->handle(
    $request = \Jan\Component\Http\Request::createFromGlobals()
);



/*
|-------------------------------------------------------
|    Send all headers to navigator
|-------------------------------------------------------
*/

$response->send();



/*
|-------------------------------------------------------
|    Terminate application
|-------------------------------------------------------
*/

$kernel->terminate($request, $response);

$configDb = [
    'driver'    => 'mysql',
    'database'  => 'mvc_framework',
    'host'      => '127.0.0.1',
    'port'      => '3306',
    'username'  => 'root',
    'password'  => '',
    'collation' => 'utf8_unicode_ci',
    'charset'   => 'utf8',
    'prefix'    => '',
    'engine'    => 'InnoDB', // MyISAM
    'options'   => [],
];


/*
$capsule = new \Jan\Component\Database\Capsule();
$capsule->addConnection($configDb);
$capsule->setAsGlobal();
$conn = $database->connect('sqlite');

//dump($capsule);

$capsule2 = \Jan\Component\Database\Capsule::getInstance();
dump($capsule2);
*/

$database = new \Jan\Component\Database\DatabaseManager();
$database->open($configDb);

dump($database);

dd($app->log());

