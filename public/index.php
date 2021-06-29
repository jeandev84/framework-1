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


echo "<h2>Database</h2>";

$fs = new \Jan\Component\FileSystem\FileSystem(
    realpath(__DIR__.'/../')
);

$configDb =  $fs->load('config/database.php');

$capsule = new \Jan\Component\Database\Capsule\Manager();
$type = $configDb['connection'];

$capsule->addConnection($configDb, $type);
$capsule->bootAsGlobal();


$database = \Jan\Component\Database\Capsule\Manager::instance();
$database->setConnection('foo', new \Jan\Component\Database\Connection\Example\FooConnection());
// dump($database->connection('foo'));
// dd($database);


$connection = \Jan\Component\Database\Capsule\Manager::connection();
$schema = new Jan\Component\Database\Schema\Schema($connection);
$migrator = new \Jan\Component\Database\Migration\Migrator($schema);
dump($migrator);


echo "<h2>Files</h2>";

dump($request->files->get('brochure', []));
dump($request->files->all());
?>
<h1>Вход</h1>
<form action="/" method="post" enctype="multipart/form-data">
    <div>
        <input type="email" name="email">
    </div>
    <div>
        <input type="password" name="password">
    </div>
    <div>
        <textarea name="content" id="" style="resize: none;"></textarea>
    </div>
    <div>
        <input type="file" name="brochure[]" multiple>
    </div>
    <div>
        <input type="file" name="upload">
    </div>
    <button type="submit">Отправить</button>
</form>

