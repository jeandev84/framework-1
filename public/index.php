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


$fs = new \Jan\Component\FileSystem\FileSystem(
    realpath(__DIR__.'/../')
);

$configDb =  $fs->load('config/database.php');;

dump($configDb);

$db = new \Jan\Component\Database\DatabaseManager();

$db->open($configDb['connection'], $configDb);


$db->setConnection('foo', new \Jan\Component\Database\Connection\Fake\FooConnection());
$db->setConnection('bar', new \Jan\Component\Database\Connection\Fake\BarConnection());


$mysqlConnection = $db->connection('mysql');
dump($mysqlConnection);

//$sqliteConnection = $db->connection('sqlite');
//dump($sqliteConnection);

$fooConnection = $db->connection('foo');
dump($fooConnection);

$db->disconnect('mysql');


dump($db->getConnections());
dd($app->log());

