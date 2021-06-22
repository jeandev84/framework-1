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

$connection = $db->connection()->getPdo();

echo '<h2>Current Connection</h2>';
dump($connection);


dump($db);
dump($db->getConnections());
dd($app->log());

