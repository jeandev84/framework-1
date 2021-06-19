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


/*
$db = new Jan\Component\Database\DatabaseManager();
$db->addConnection(new \Jan\Component\Database\Connection\FooConnection());
*/

// dump($db->getConnection());

/*
$dsn = 'mysql:host=127.0.0.1;dbname=mvc_framework;port=3306;charset=utf8';
$pdo = new \PDO($dsn, 'root', '');
*/

$configDb = [
    'driver'    => 'sqlite',
    'database'  => '../db.sqlite',
    'host'      => '127.0.0.1',
    'port'      => '3306',
    'username'  => '',
    'password'  => '',
    'collation' => 'utf8_unicode_ci',
    'charset'   => 'utf8',
    'prefix'    => '',
    'engine'    => 'InnoDB', // MyISAM
    'options'   => [],
];

/*
$configDb = [
    'driver'    => 'sqlite',
    'database'  => '../db.sqlite',
    'host'      => '127.0.0.1',
    'port'      => '3306',
    'username'  => '',
    'password'  => '',
    'collation' => 'utf8_unicode_ci',
    'charset'   => 'utf8',
    'prefix'    => '',
    'engine'    => 'InnoDB', // MyISAM
    'options'   => [],
];

$db = new Jan\Component\Database\DatabaseManager($configDb);
dump($db);
*/

$config = new \Jan\Component\Database\Configuration($configDb);
$database = new \Jan\Component\Database\DatabaseManager();
//$database->setConnection();
//$database->setConnections([
// new MySQL($config)
//);


dd($app->log());

