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
    public function add(Route $route): Route
    {
        $this->routes[] = $route;

        if ($name = $route->getName()) {
            $this->namedRoutes[$name] = $route;
        }

        return $route;
    }



    /**
     * @return Route[]
    */
    public function getRoutes(): array
    {
        return $this->routes;
    }



    /**
     * @param array $routes
    */
    public function setRoutes(array $routes)
    {
        foreach ($routes as $route) {
            $this->addRouteFromArray($route);
        }
    }



    /**
     * @param array $items
     * @return Route
    */
    public function addRouteFromArray(array $items): Route
    {
        $route = new Route();

        foreach ($items as $k => $v) {
            $route[$k] = $v;
        }

        return $this->add($route);
    }




    /**
     * add resources
     *
     * @param array $routes
     * @return RouteCollection
    */
    public function addRouteResource(array $routes): RouteCollection
    {
        $this->setRoutes($routes);

        $this->resources = array_merge($this->resources, $routes);

        return $this;
    }


    /**
     * get resources
     *
     * @return array
    */
    public function getResources(): array
    {
        return $this->resources;
    }



    /**
     * get named routes
     *
     * @return array
    */
    public function getNamedRoutes(): array
    {
        foreach ($this->getRoutes() as $route) {
            if ($name = $route->getName()) {
                if (! \array_key_exists($name, $this->namedRoutes)) {
                    $this->namedRoutes[$name] = $route;
                }
            }
        }

        return $this->namedRoutes;
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
     * @return Route|null
     * @throws \Exception
    */
    public function getRoute($name): ?Route
    {
        if(! $this->has($name)) {
            return null;
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

        foreach ($this->getRoutes() as $route) {
            $routes[$route->toStringMethod()][] = $route;
        }

        return $routes;
    }
}