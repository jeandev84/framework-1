<?php
namespace Jan\Express;


use Jan\Component\Container\Container;
use Jan\Component\Routing\Route;
use Jan\Component\Routing\Router;

/**
 * Class App
 * @package Jan\Express
*/
class App
{

    /**
     * @var array
    */
    protected $config;



    protected $routes = [];



    /**
     * @var Container
    */
    protected $container;



    /**
     * App constructor.
     * @param Container $container
    */
    public function __construct(Container $container)
    {
         $this->container = $container;
    }




    /**
     * @param string $path
     * @param \Closure $closure
     * @return void
     */
    public function get(string $path, \Closure $closure) {
       $route = new Route(['GET'], $path, $closure);
       $this->routes[] = $route;
    }



    public function run()
    {

    }
}