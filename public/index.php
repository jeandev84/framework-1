<?php


/*
|----------------------------------------------------------------------
|   Autoloader classes and dependencies of application
|----------------------------------------------------------------------
*/

use Jan\Component\Routing\Router;

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

$fs = new \Jan\Component\FileSystem\FileSystem(
    realpath(__DIR__.'/../')
);

$configDb =  $fs->load('config/database.php');
dump($configDb);

$db = new \Jan\Component\Database\DatabaseManager();

$db->open($configDb['connection'], $configDb);

$connection = $db->connection()->getPdo();

echo '<h2>Current Connection</h2>';
dump($connection);


dump($db);
dump($db->getConnections());
*/

echo "<h2>Database</h2>";

$fs = new \Jan\Component\FileSystem\FileSystem(
    realpath(__DIR__.'/../')
);

dump($fs->scan('database/migrations'));


$configDb =  $fs->load('config/database.php');

$capsule = new \Jan\Component\Database\Capsule\Manager();
$type = $configDb['connection'];

$capsule->addConnection($type, $configDb);
$capsule->bootAsGlobal();

$database = \Jan\Component\Database\Capsule\Manager::instance();

$connection = \Jan\Component\Database\Capsule\Manager::connection();

/* dump($database->connection('sqlite')); */
dump($database->connection());

dd($app->log());

