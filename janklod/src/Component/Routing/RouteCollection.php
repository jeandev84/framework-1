<?php
namespace Jan\Component\Routing;


/**
 * Class RouteCollection
 * @package Jan\Component\Routing
 */
class RouteCollection
{

    /**
     * @var array
     */
    protected $routes = [];



    /**
     * @var array
     */
    protected $resources = [];



    /**
     * @var array
     */
    protected $namedRoutes = [];



    /**
     * @param Route $route
     * @return Route
     */
    public function add(Route $route)
    {
        $this->routes[] = $route;

        return $route;
    }



    /**
     * @return Route[]
     */
    public function getRoutes()
    {
        return $this->routes;
    }


    /**
     * @param array $routes
     */
    public function setRoutes(array $routes)
    {
        foreach ($routes as $route) {
            if(\is_array($route)) {
                $route = $this->makeRouteFromArray($route);
            }

            if($route instanceof Route) {
                $this->add($route);
            }
        }
    }


    /**
     * @param array $items
     * @return Route
    */
    public function makeRouteFromArray(array $items): Route
    {
        $route = new Route();
        foreach ($items as $k => $v) {
            $route[$k] = $v;
        }
        return $route;
    }


//    /**
//     * add resources
//     *
//     * @param array $routes
//     * @return RouteCollection
//     */
//    public function addRouteResource(array $routes)
//    {
//        $this->setRoutes($routes);
//
//        $this->resources = array_merge($this->resources, $routes);
//
//        return $this;
//    }
//
//
//    /**
//     * get resources
//     *
//     * @return array
//     */
//    public function getResources()
//    {
//        return $this->resources;
//    }



    /**
     * get named routes
     *
     * @return array
     */
    public function getNamedRoutes(): array
    {
        $namedRoutes = [];
        foreach ($this->getRoutes() as $route) {
            if($name = $route->getName()) {
                $namedRoutes[$name] = $route;
            }
        }

        return $namedRoutes;
    }



    /**
     * @param $name
     * @return bool
     */
    public function has($name): bool
    {
        return \array_key_exists($name, $this->getNamedRoutes());
    }


    /**
     * @param $name
     * @return Route
     * @throws \Exception
    */
    public function getRoute($name): Route
    {
        if(! $this->has($name)) {
            throw new \Exception('route ('. $name . ') does not exist.');
        }

        return $this->getNamedRoutes()[$name];
    }



    /**
     * get routes by method
     *
     * @return array
     */
    public function getRoutesByMethod(): array
    {
        $routes = [];

        foreach ($this->getRoutes() as $route)
        {
            /** @var Route $route */
            $routes[$route->toStringMethod()][] = $route;
        }

        return $routes;
    }
}