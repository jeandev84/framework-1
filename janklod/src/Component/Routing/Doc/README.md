# ROUTING


```
<?php

use App\Entity\User;
use Jan\Component\Container\Container;


require_once __DIR__.'/../vendor/autoload.php';


$router = new \Jan\Component\Routing\Router('http://localhost:8080');

$router->get('/', function () {
    return 'Welcome!';
}, 'home');

$router->get('/foo', function () {
    return 'Foo!';
}, 'foo');


$router->get('/about', '\App\Controller\PageController@about');


$route = $router->match($_SERVER['REQUEST_METHOD'], $uri = $_SERVER['REQUEST_URI']);

if(! $route) {
    dd('Not found ( '. $uri . ' )');
}

dump($route);
dump($router->getRoutesByMethod());


echo $router->url('home');
echo "<br>";
echo $router->url('foo');
echo "<br>";
echo $router->generate('foo');
echo "<br>";
echo "Ignore params <br>";
echo $router->generate('foo', ['id' => 4, 'name' => 'janklod']);
```