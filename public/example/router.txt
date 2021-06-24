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

$configDb =  $fs->load('config/database.php');

$capsule = new \Jan\Component\Database\Capsule\Manager();
$type = $configDb['connection'];

$capsule->addConnection($type, $configDb);
$capsule->bootAsGlobal();

$database = \Jan\Component\Database\Capsule\Manager::instance();

$connection = \Jan\Component\Database\Capsule\Manager::connection();

/* dump($database->connection('sqlite')); */
dump($database->connection());


echo "<h2>Routing</h2>";

$router = new Router();

$router->group(function (Router $router) {
    $router->get('/', function () {
        return 'Hello world';
    })->name('home')->middleware(\App\Middleware\AuthenticatedMiddleware::class);


    $router->map('GET|POST', '/contact', function () {
        return '<form action="/contact" method="POST">
      <div><input type="email" name="email" value=""></div>
      <div><input type="password" name="password" value=""></div>
      <div><button type="submit">Отправить</button></div>
    </form>';
    })->name('contact');


    $router->get('/post/{id}', function () {
        return 'Post id : 1';
    });

    $router->post('/upload', 'UploadController@index', 'upload');

}, [
    'prefix' => '/admin',
    'name' => 'admin.',
    'middleware' => \App\Middleware\FooMiddleware::class,
    'namespace' => 'Admin\\'
]);


$router->get('/foo/{id}', function (\Jan\Component\Http\Request $request, \Jan\Component\Http\Response $response) {

})->whereDigital('id');

dump($router->getRoutes());


$route = $router->match($_SERVER['REQUEST_METHOD'], $uri = $_SERVER['REQUEST_URI']);

if (! $route) {
    dd('Route '. $uri . ' not found.');
}

dump($route);

dd($app->log());

