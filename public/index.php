<?php


/*
|----------------------------------------------------------------------
|   Autoloader classes and dependencies of application
|----------------------------------------------------------------------
*/

use Jan\Component\Database\Schema\BluePrint;
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

/*
dump($fs->scan('database/migrations'));
dump($fs->toArray('config/database.php'));
*/

$configDb =  $fs->load('config/database.php');

$capsule = new \Jan\Component\Database\Capsule\Manager();
$type = $configDb['connection'];

$capsule->addConnection($type, $configDb);
$capsule->bootAsGlobal();

$database = \Jan\Component\Database\Capsule\Manager::instance();
dump($database);
/*
dump($database->connection('sqlite'));
dump($database->connection());
*/

$connection = \Jan\Component\Database\Capsule\Manager::connection();

$schema = new Jan\Component\Database\Schema\Schema($connection);

/*
dump($schema);
$schema->create('users', function (BluePrint $table) {

});
*/

$migrator = new \Jan\Component\Database\Migration\Migrator($schema);
dump($migrator);

/* $migrator->install(); */


$otherMigrationFiles = $fs->resources('/migrations/*.php');

dump($otherMigrationFiles);

$otherMigrations = [];

if ($otherMigrationFiles) {
    foreach ($otherMigrationFiles as $otherMigrationFile) {
        require_once $otherMigrationFile;
        $migration = pathinfo($otherMigrationFile, PATHINFO_FILENAME);
        $otherMigrations[] = new $migration();
    }
}


$defaultMigrations = [
    new \App\Migration\Version202106051623(),
    new \App\Migration\Version202106051624()
];

$migrations = array_merge($defaultMigrations, $otherMigrations);

$migrator->setMigrations($migrations);

/* $migrator->rollback(); */

$migrator->migrate();


dump($migrator->getMigrationFiles());


dd($app->log());

